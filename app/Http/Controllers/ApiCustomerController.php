<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Master\Customer;
use App\Master\CustomerProspek;
use DB;

class ApiCustomerController extends Controller
{
    public function updateFields(Request $request, $id)
    {
        $pic = $request->header('X-USER-PIC'); // PIC pengirim
        $notes = $request->input('notes', 'Update dari Project A');
    
        if (!$pic) {
            return response()->json([
                'success' => false,
                'message' => 'PIC tidak ditemukan'
            ], 400);
        }
    
        $tableAddress = null;
        $addressId = null;
    
        // ==============================
        // 1. CEK CUSTOMER EXISTING
        // ==============================
        $customer = DB::table('master_customers')
            ->where('id', $id)
            ->where('pic', $pic)
            ->first();
    
        if ($customer) {
    
            $address = DB::table('master_customer_other_addresses')
                ->where('customer_id', $id)
                ->first();
    
            if ($address) {
                $tableAddress = 'master_customer_other_addresses';
                $addressId = $address->id;
            }
        }
    
        // ==============================
        // 2. CEK CUSTOMER PROSPEK
        // ==============================
        if (!$addressId) {
    
            $customerProspek = DB::table('master_customers_prospek')
                ->where('id', $id)
                ->where('pic', $pic)
                ->first();
    
            if ($customerProspek) {
    
                $address = DB::table('master_customer_other_addresses_prospek')
                    ->where('customer_id', $id)
                    ->first();
    
                if ($address) {
                    $tableAddress = 'master_customer_other_addresses_prospek';
                    $addressId = $address->id;
                }
            }
        }
    
        if (!$addressId) {
            return response()->json([
                'success' => false,
                'message' => 'Customer atau address tidak ditemukan untuk PIC ini'
            ], 404);
        }
    
        // ==============================
        // 3. UPDATE ADDRESS
        // ==============================
        DB::table($tableAddress)
            ->where('id', $addressId)
            ->update([
                'last_updated' => now(),
                'last_updated_notes' => $notes,
            ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Customer address updated successfully'
        ]);
    }
}