<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function allProduct()
    {
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }

    public function addProduct()
    {
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $active_vendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        return view('backend.product.product_add', compact('brands', 'categories', 'active_vendor'));
    }
}
