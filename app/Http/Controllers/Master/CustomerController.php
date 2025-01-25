<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Master\Customer;
use DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Melindungi semua method di controller ini
    }

    public function index(Request $request)
    {
        $data['customers'] = Customer::get();

        return view('master.customer.index', $data);
    }
}