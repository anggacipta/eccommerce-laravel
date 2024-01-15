<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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

    public function storeProduct(Request $request)
    {
        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(800, 1800)->save('upload/product/thumbnail/' . $name_gen);
        $save_url = 'upload/product/thumbnail/' . $name_gen;

        $product_id = Product::insertGetId([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,
            'product_slug' => strtolower(str_replace(' ', '-', $request->product_name)),

            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,
            'product_color' => $request->product_color,
            'product_size' => $request->product_size,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'product_thumbnail' => $save_url,
            'vendor_id' => $request->vendor_id,
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Multiple Images upload
        $images = $request->file('multi_img');
        foreach ($images as $img) {
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            Image::make($img)->resize(800, 1800)->save('upload/product/multi-img/' . $make_name);
            $upload_path = 'upload/product/multi-img/' . $make_name;

            MultiImg::insert([
                'product_id' => $product_id,
                'photo_name' => $upload_path,
                'created_at' => Carbon::now()
            ]);
        } // End foreach

        $notification = array(
            'message' => 'Product inserted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);
    }

    public function editProduct($id)
    {
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategories = SubCategory::latest()->get();
        $active_vendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        $products = Product::findOrFail($id);
        return view('backend.product.product_edit', compact('brands', 'subcategories', 'categories', 'active_vendor', 'products'));
    }

    public function updateProduct(Request $request)
    {
        $product_id = $request->id;

        Product::findOrFail($product_id)->update([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,
            'product_slug' => strtolower(str_replace(' ', '-', $request->product_name)),

            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,
            'product_color' => $request->product_color,
            'product_size' => $request->product_size,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'vendor_id' => $request->vendor_id,
            'status' => 1,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Product updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);
    }

    public function updateProductThumbnail(Request $request)
    {
        $product_id = $request->id;
        $old_image = $request->old_image;

        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(800, 1800)->save('upload/product/thumbnail/' . $name_gen);
        $save_url = 'upload/product/thumbnail/' . $name_gen;

        if (file_exists($old_image)) {
            unlink($old_image);
        }

        Product::findOrFail($product_id)->update([
            'product_thumbnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Product thumbnail updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);
    }

}
