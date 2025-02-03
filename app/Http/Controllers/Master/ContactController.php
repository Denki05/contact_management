<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Master\Contact;
use App\Master\Customer;
use DB;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Melindungi semua method di controller ini
    }

    public function index(Request $request)
    {
        $data['contacts'] = Contact::get();

        return view('master.contact.index', $data); // Mengarahkan ke view
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

        $contact = new Contact();
        $contact->manage_id     = $request->manage_id;
        $contact->is_for        = 0;
        $contact->name          = $request->name;
        $contact->dob           = $request->dob;
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
                'master_customer_other_addresses.text_provinsi',
            )
            ->where('master_customers.status', 1)
            ->get();

        return view('master.contact.edit', $data); // Mengarahkan ke view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'manage_id'  => 'required',
            'name'       => 'required',
            'dob'        => 'required|date',
            'position'   => 'required',
            'phone'      => 'required|numeric',
            'email'      => 'nullable|email',
            'ktp'        => 'required',
            'npwp'       => 'required',
            'status'     => 'required|in:0,1',
            'image_ktp'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_npwp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->manage_id = $request->manage_id; // Menyimpan manage_id
        $contact->name = $request->name;
        $contact->dob = $request->dob;
        $contact->position = $request->position;
        $contact->phone = $request->phone;
        $contact->email = $request->email;
        $contact->ktp = $request->ktp;
        $contact->npwp = $request->npwp;
        $contact->status = 1; // Pastikan ini sesuai dengan sistem status kamu
        $contact->is_for = 0;

        // Handle Upload Image KTP
        if ($request->hasFile('image_ktp')) {
            // Hapus file lama jika ada
            if ($contact->image_ktp) {
                Storage::disk('public')->delete('superuser_assets/media/master/contact/' . $contact->image_ktp);
            }

            $imageKtpName = uniqid().'_ktp.'.$request->file('image_ktp')->getClientOriginalExtension();
            $request->file('image_ktp')->move(public_path('superuser_assets/media/master/contact/'), $imageKtpName);
            $contact->image_ktp = $imageKtpName;
        }

        // Handle Upload Image NPWP
        if ($request->hasFile('image_npwp')) {
            // Hapus file lama jika ada
            if ($contact->image_npwp) {
                Storage::disk('public')->delete('superuser_assets/media/master/contact/' . $contact->image_npwp);
            }

            $imageNpwpName = uniqid().'_npwp.'.$request->file('image_npwp')->getClientOriginalExtension();
            $request->file('image_npwp')->move(public_path('superuser_assets/media/master/contact/'), $imageNpwpName);
            $contact->image_npwp = $imageNpwpName;
        }

        if ($contact->save()) {
            return redirect()->route('master.contact.index')->with('success', 'Data berhasil diperbarui.');
        } else {
            return back()->with('error', 'Data gagal diperbarui.');
        }
        

        return redirect()->route('master.contact.index')->with('success', 'Data berhasil diperbarui.');
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