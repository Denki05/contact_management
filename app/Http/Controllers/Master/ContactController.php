<<<<<<< HEAD
<<<<<<< HEAD
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
use Illuminate\Pagination\LengthAwarePaginator;

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
        $existing = Customer::select('master_customer_other_addresses.id', 'master_customer_other_addresses.name', 'master_customer_other_addresses.text_kota')
            ->leftJoin('master_customers', 'master_customers.id', '=', 'master_customer_other_addresses.customer_id')
            ->where('master_customers.status', 1)
            ->get()
            ->map(function ($c) {
                $c->source = 'existing';
                $c->encoded_id = $c->id; 
                return $c;
            });

        // Ambil customer prospek
        $prospek = CustomerProspek::select('master_customer_other_addresses_prospek.id', 'master_customer_other_addresses_prospek.name', 'master_customer_other_addresses_prospek.text_kota')
            ->leftJoin('master_customers_prospek', 'master_customers_prospek.id', '=', 'master_customer_other_addresses_prospek.customer_id')
            ->where('master_customers_prospek.status', 1)
            ->get()
            ->map(function ($c) {
                $c->source = 'prospek';
                $c->encoded_id = $c->id; 
                return $c;
            });

        // Gabungkan dan urutkan
        $data['customers'] = $existing->merge($prospek)->sortBy('name')->values();

        return view('master.contact.partials.select_customer_modal', $data);

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
                return response()->json(['errors' => ['manage_id' => ['Customer/Store tidak ditemukan.']]], 422);
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
    
            if ($request->ajax()) {
                return response()->json(['message' => 'Data berhasil ditambahkan.']);
            }
            return redirect()->route('master.contact.find')->with('success', 'Data berhasil ditambahkan.');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan contact: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['errors' => ['exception' => [$e->getMessage()]]], 500);
            }
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
    
    public function partialIndex(Request $request)
    {
        $perPage = 5;
        $search = $request->input('search'); // Ambil keyword dari request
    
        $rows = DB::table('master_contacts')
            ->leftJoin('master_customer_other_addresses as mc', 'mc.id', '=', 'master_contacts.manage_id')
            ->leftJoin('master_customer_other_addresses_prospek as mp', 'mp.id', '=', 'master_contacts.manage_id')
            ->where('master_contacts.status', Contact::STATUS['ACTIVE'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('master_contacts.name', 'like', "%{$search}%")
                      ->orWhere('mc.name', 'like', "%{$search}%")
                      ->orWhere('mp.name', 'like', "%{$search}%");
                });
            })
            ->select(
                'master_contacts.id',
                'master_contacts.manage_id',
                'master_contacts.name',
                'master_contacts.phone',
                'master_contacts.position',
                'master_contacts.dob',
                'master_contacts.ktp',
                'master_contacts.npwp',
                'master_contacts.status',
                'mc.name as existing_name',
                'mp.name as prospek_name',
                'mc.text_kota as existing_kota',
                'mp.text_kota as prospek_kota'
            )
            ->orderByRaw('COALESCE(mc.name, mp.name) ASC')
            ->paginate($perPage)
            ->appends(['search' => $search]); // supaya pagination mempertahankan keyword
    
        // Transform data untuk menambahkan nama customer + kota
        $rows->getCollection()->transform(function ($r) {
            $name  = $r->existing_name ?: $r->prospek_name ?: 'Customer Tidak Dikenal';
            $kota  = $r->existing_name ? $r->existing_kota : ($r->prospek_name ? $r->prospek_kota : null);
            $r->customer_name = $name . ($kota ? " - $kota" : '');
            return $r;
        });
    
        return view('master.contact.partials.index', [
            'rows' => $rows,
            'search' => $search,
        ]);
    }
    
    // Partial create: pilih customer dulu
    public function partialCreate(Request $request)
    {
        // jika customer_id dikirim, ambil data customer
        $selected_customer = null;
        if ($request->filled('customer_id')) {
            $selected_customer = $this->findCustomerByManageId($request->customer_id);
        }
    
        $encoded_id = $selected_customer ? $selected_customer->id : 0;
    
        return view('master.contact.partials.create', compact('selected_customer', 'encoded_id'));
    }

=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
}