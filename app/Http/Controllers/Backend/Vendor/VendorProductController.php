<?php

namespace App\Http\Controllers\Backend\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    public function vendorAllProduct()
    {
        $id = Auth::user()->id;
        $products = Product::where('vendor_id', $id)->latest()->get();
        return view('backend.vendor.product.all_product_vendor', compact('products'));
    }
}
