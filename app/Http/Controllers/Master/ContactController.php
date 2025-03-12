<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Master\Contact;
use App\Master\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Melindungi semua method di controller ini
    }

    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter berdasarkan toko jika ada input store_id
        if ($request->filled('store_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('id', $request->store_id);
            });
        }

        $data['contacts'] = $query->get();
        
        // Ambil daftar toko dengan text_kota
        $data['stores'] = Customer::select('id', 'name', 'text_kota')->get();

        return view('master.contact.index', $data);
    }

    public function create(Request $request)
    {
        $data['customers'] = DB::table('master_customers')
        ->leftJoin('master_customer_other_addresses', 'master_customer_other_addresses.customer_id', '=', 'master_customers.id')
        ->select(
            'master_customer_other_addresses.id',
            'master_customer_other_addresses.name',
            'master_customer_other_addresses.text_kota',
            'master_customer_other_addresses.text_provinsi',
        )
        ->where('master_customers.status', 1)
        ->get();

        return view('master.contact.create', $data); // Mengarahkan ke view
    }

    public function store(Request $request)
    {
        $request->validate([
            'manage_id'  => 'required',
            'name'       => 'required',
            'dob'        => 'required|date',
            'posisi'     => 'required',
            'phone'      => 'required|numeric',
            'email'      => 'nullable|email',
            'ktp'        => 'required',
            'image_ktp'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'npwp'       => 'required',
            'image_npwp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $dob = request('dob'); // Misal: "15-07"
        $dob = \Carbon\Carbon::createFromFormat('d-m', $dob)->format('1900-m-d'); // Set default tahun ke 1900

        $contact = new Contact();
        $contact->manage_id     = $request->manage_id;
        $contact->is_for        = 0;
        $contact->name          = $request->name;
        $contact->dob           = $dob;
        $contact->position      = $request->posisi;
        $contact->phone         = $request->phone;
        $contact->email         = $request->email;
        $contact->ktp           = $request->ktp;
        $contact->npwp          = $request->npwp;
        $contact->status        = Contact::STATUS['ACTIVE'];

        // Handle Upload File
        if ($request->hasFile('image_ktp')) {
            $image_ktp_path = $request->file('image_ktp')->store('contact/ktp', 'public');
            $contact->image_ktp = $image_ktp_path;
        }

        if ($request->hasFile('image_npwp')) {
            $image_npwp_path = $request->file('image_npwp')->store('contact/npwp', 'public');
            $contact->image_npwp = $image_npwp_path;
        }

        $contact->save();

        return redirect()->route('master.contact.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $data['contact'] = Contact::findOrFail($id);
        $data['customers'] = DB::table('master_customers')
            ->leftJoin('master_customer_other_addresses', 'master_customer_other_addresses.customer_id', '=', 'master_customers.id')
            ->select(
                'master_customer_other_addresses.id',
                'master_customer_other_addresses.name',
                'master_customer_other_addresses.text_kota',
                'master_customer_other_addresses.text_provinsi'
            )
            ->where('master_customers.status', 1)
            ->get();

        return view('master.contact.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'manage_id'  => 'required|exists:master_customer_other_addresses,id',
            'name'       => 'required|string|max:255',
            'dob'        => 'nullable|date|before:today',
            'position'     => 'required|string|max:255',
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

            // Direktori penyimpanan
            $path = 'superuser_assets/media/master/contact/';

            // Cek apakah direktori ada, jika tidak buat
            if (!Storage::exists('public/' . $path)) {
                Storage::makeDirectory('public/' . $path);
            }

            // Handle Upload Image KTP
            if ($request->hasFile('image_ktp')) {
                if ($contact->image_ktp) {
                    Storage::disk('public')->delete($path . $contact->image_ktp);
                }
                $imageKtpName = uniqid().'_ktp.'.$request->file('image_ktp')->extension();
                $request->file('image_ktp')->storeAs('public/' . $path, $imageKtpName);
                $contact->image_ktp = $imageKtpName;
            }

            // Handle Upload Image NPWP
            if ($request->hasFile('image_npwp')) {
                if ($contact->image_npwp) {
                    Storage::disk('public')->delete($path . $contact->image_npwp);
                }
                $imageNpwpName = uniqid().'_npwp.'.$request->file('image_npwp')->extension();
                $request->file('image_npwp')->storeAs('public/' . $path, $imageNpwpName);
                $contact->image_npwp = $imageNpwpName;
            }

            $dob = request('dob'); // Misal: "15-07"
            $dob = \Carbon\Carbon::createFromFormat('d-m', $dob)->format('1900-m-d'); // Set default tahun ke 1900

            $contact->manage_id  = $request->manage_id;
            $contact->is_for = 0;
            $contact->name = $request->name;
            $contact->phone = $request->phone;
            $contact->email = $request->email;
            $contact->position = $request->position;
            $contact->dob = $dob;
            $contact->ktp = $request->ktp;
            $contact->npwp = $request->npwp;

            $contact->save();

            return redirect()->route('master.contact.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $data['contact'] = Contact::findOrFail($id);

        return view('master.contact.show', $data); // Mengarahkan ke view
    }

    public function destroy(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->status = Contact::STATUS['DELETED'];
        $contact->save();

        return redirect()->route('master.contact.index')->with('success', 'Data berhasil dihapus.');
    }
}