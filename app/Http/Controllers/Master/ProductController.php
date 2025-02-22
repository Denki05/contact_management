<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Master\Product;
use DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Melindungi semua method di controller ini
    }

    public function index()
    {
        $data['products'] = Product::get();

        return view('master.product.index', $data);
    }

    public function upload_property(Request $request, $encodedId)
    {
        try {
            $decodedId = base64_decode($encodedId);

            \Log::info('Decoded ID:', ['id' => $decodedId]);
            $product = Product::findOrFail($decodedId);
            if (!$product) {
                return back()->with('error', 'Produk tidak ditemukan!');
            }

            $validator = Validator::make($request->all(), [
                'img_thumbnail'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'img_hd'         => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
                'video_product'  => 'nullable|mimes:mp4,mov,avi,flv|max:51200',
                'video_sosmed'   => 'nullable|mimes:mp4,mov,avi,flv|max:51200',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $path = 'uploads/products/';

            if ($request->hasFile('img_thumbnail')) {
                if ($product->image) Storage::delete('public/' . $product->image);
                $product->image = $request->file('img_thumbnail')->store($path, 'public');
            }

            if ($request->hasFile('img_hd')) {
                if ($product->image_hd) Storage::delete('public/' . $product->image_hd);
                $product->image_hd = $request->file('img_hd')->store($path, 'public');
            }

            if ($request->hasFile('video_product')) {
                if ($product->videos_product_1) Storage::delete('public/' . $product->videos_product_1);
                $product->videos_product_1 = $request->file('video_product')->store($path, 'public');
            }

            if ($request->hasFile('video_sosmed')) {
                if ($product->videos_product_2) Storage::delete('public/' . $product->videos_product_2);
                $product->videos_product_2 = $request->file('video_sosmed')->store($path, 'public');
            }

            $product->save();

            return redirect()->route('master.product.index')->with('success', 'Media produk berhasil diunggah!');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}