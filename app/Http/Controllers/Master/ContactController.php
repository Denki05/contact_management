<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Master\Contact;
use App\Master\Customer;

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
        $data['customers'] = Customer::get();

        return view('master.contact.create', $data); // Mengarahkan ke view
    }

    public function store()
    {
        return redirect()->route('master.contact.index'); // Mengarahkan ke route
    }
}