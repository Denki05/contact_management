<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Master\Contact;
use App\Master\Customer;
use App\Master\CustomerProspek;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access');
        }
    
        // Ambil contact aktif (tanpa eager load palsu)
        $query = Contact::where('status', Contact::STATUS['ACTIVE']);
    
        // Filter toko
        if ($request->filled('store_id')) {
            $query->where('manage_id', $request->store_id);
        }
    
        // Filter nama kontak
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        $data['contacts'] = $query->paginate(15);
    
        // Ambil daftar toko (existing dan prospek)
        $existing = Customer::select('id', 'name', 'text_kota');
        $prospek  = CustomerProspek::select('id', 'name', 'text_kota');
    
        $data['stores'] = $existing->unionAll($prospek)->get();
    
        return view('master.contact.find', $data);
    }


    public function newContact()
    {
        // Ambil customer existing
        $existing = Customer::select('id', 'name', 'text_kota')
            ->get()
            ->map(function ($c) {
                $c->source = 'existing';
                $c->encoded_id = $c->id; 
                return $c;
            });

        // Ambil customer prospek
        $prospek = CustomerProspek::select('id', 'name', 'text_kota')
            ->get()
            ->map(function ($c) {
                $c->source = 'prospek';
                $c->encoded_id = $c->id; 
                return $c;
            });

        // Gabungkan dan urutkan
        $data['customers'] = $existing->merge($prospek)->sortBy('name')->values();

        return view('master.contact.new', $data);
    }

    public function create(Request $request, $encoded_id)
    {
        // Langsung gunakan ID tanpa validasi format titik
        $manage_id = trim($encoded_id);

        if ($manage_id === '') {
            return redirect()->route('master.contact.new')
                ->with('error', 'Customer ID kosong. Mohon pilih ulang Customer.');
        }

        $data['selected_customer'] = $this->findCustomerByManageId($manage_id);

        if (!$data['selected_customer']) {
            return redirect()->route('master.contact.new')
                ->with('error', 'Customer tidak ditemukan. Mohon pilih ulang.');
        }

        $data['encoded_id'] = $manage_id;

        return view('master.contact.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'manage_id' => 'required|string',
                'name' => 'required|max:255',
                'dob' => 'required|date',
                'posisi' => 'required|max:100',
                'phone' => 'required|numeric',
                'email' => 'nullable|email',
                'ktp' => 'required',
                'npwp' => 'nullable',
                'image_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'image_npwp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'address' => 'nullable',
            ]);

            $manage_id = $request->manage_id;

            $customer = $this->findCustomerByManageId($manage_id);
            if (!$customer) {
                return back()->with('error', 'Customer/Store tidak ditemukan.')->withInput();
            }

            $contact = new Contact();
            $contact->manage_id = $manage_id;
            $contact->name = $request->name;
            $contact->dob = Carbon::parse($request->dob)->format('Y-m-d');
            $contact->position = $request->posisi;
            $contact->phone = $request->phone;
            $contact->email = $request->email;
            $contact->ktp = $request->ktp;
            $contact->npwp = $request->npwp;
            $contact->address = $request->address;
            $contact->is_for = 0;
            $contact->status = Contact::STATUS['ACTIVE'];

            $path = 'superuser_assets/media/master/contact/';
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            if ($request->hasFile('image_ktp')) {
                $imageKtpName = str_replace('.', '_', $manage_id) . '_ktp_' . time() . '.' . $request->file('image_ktp')->extension();
                $request->file('image_ktp')->storeAs($path, $imageKtpName, 'public');
                $contact->image_ktp = $imageKtpName;
            }

            if ($request->hasFile('image_npwp')) {
                $imageNpwpName = str_replace('.', '_', $manage_id) . '_npwp_' . time() . '.' . $request->file('image_npwp')->extension();
                $request->file('image_npwp')->storeAs($path, $imageNpwpName, 'public');
                $contact->image_npwp = $imageNpwpName;
            }

            $contact->save();

            return redirect()->route('master.contact.find')->with('success', 'Data berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            dd($e);
            \Log::error('Gagal menyimpan contact: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    /**
     * Cari customer berdasarkan ID gabungan (contoh: 191.1 atau 191)
     * Cek di Customer dulu, jika tidak ada cek di CustomerProspek
     */
    private function findCustomerByManageId($manage_id)
    {
        $id = trim($manage_id);
        if ($id === '') {
            return null;
        }

        $customer = Customer::find($id);
        if ($customer) {
            $customer->source_label = 'existing';
            return $customer;
        }

        $prospek = CustomerProspek::find($id);
        if ($prospek) {
            $prospek->source_label = 'prospek';
            return $prospek;
        }

        return null;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'manage_id'  => 'required|string',
            'name'       => 'required|string|max:255',
            'dob'        => 'nullable|date|before:today',
            'posisi'     => 'required|string|max:255',
            'phone'      => 'required|numeric|digits_between:10,15',
            'email'      => 'nullable|email|max:255',
            'ktp'        => 'nullable|string|regex:/^[0-9]{16}$/',
            'npwp'       => 'nullable|string|regex:/^[0-9]{15}$/',
            'image_ktp'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_npwp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $contact = Contact::findOrFail($id);
            $contact->fill($request->except(['image_ktp', 'image_npwp']));

            $path = 'superuser_assets/media/master/contact/';
            if (!Storage::exists('public/' . $path)) {
                Storage::makeDirectory('public/' . $path);
            }

            if ($request->hasFile('image_ktp')) {
                if ($contact->image_ktp && Storage::disk('public')->exists($path . $contact->image_ktp)) {
                    Storage::disk('public')->delete($path . $contact->image_ktp);
                }
                $imageKtpName = uniqid().'_ktp.'.$request->file('image_ktp')->extension();
                $request->file('image_ktp')->storeAs('public/' . $path, $imageKtpName);
                $contact->image_ktp = $imageKtpName;
            }

            if ($request->hasFile('image_npwp')) {
                if ($contact->image_npwp && Storage::disk('public')->exists($path . $contact->image_npwp)) {
                    Storage::disk('public')->delete($path . $contact->image_npwp);
                }
                $imageNpwpName = uniqid().'_npwp.'.$request->file('image_npwp')->extension();
                $request->file('image_npwp')->storeAs('public/' . $path, $imageNpwpName);
                $contact->image_npwp = $imageNpwpName;
            }

            $dob = $request->dob ? "1900-" . Carbon::parse($request->dob)->format('m-d') : null;

            $contact->dob = $dob;
            $contact->save();

            return redirect()->route('master.contact.find')->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Gagal update data contact: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access');
        }
    
        try {
            // Ambil contact berdasarkan id
            $contact = Contact::findOrFail($id);
    
            // Cari customer/prospek berdasarkan manage_id
            $relatedCustomer = \App\Master\Customer::find($contact->manage_id);
    
            if (!$relatedCustomer) {
                $relatedCustomer = \App\Master\CustomerProspek::find($contact->manage_id);
            }
    
            // Simpan ke array data
            $data['contact'] = $contact;
            $data['relatedCustomer'] = $relatedCustomer;
            $data['back_route'] = route('master.contact.find');
    
            return view('master.contact.show', $data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('master.contact.find')
                ->with('error', 'Kontak tidak ditemukan.');
        }
    }


    public function destroy(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->status = Contact::STATUS['DELETED'];
        $contact->save();

        return redirect()->route('master.contact.find')->with('success', 'Data berhasil dihapus.');
    }
}