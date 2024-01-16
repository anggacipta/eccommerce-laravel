<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function allCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.admin.category.category_all', compact('categories'));
    }

    public function addCategory()
    {
        return view('backend.admin.category.category_add');
    }

    public function storeCategory(Request $request)
    {
        $image = $request->file('category_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(300, 300)->save('upload/category/' . $name_gen);
        $save_url = 'upload/category/' . $name_gen;

        Category::insert([
           'category_name' => $request->category_name,
           'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
           'category_image' =>  $save_url
        ]);

        $notification = array(
            'message' => 'Category inserted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.admin.category.category_edit', compact('category'));
    }

    public function updateCategory(Request $request)
    {
        $id = $request->id;
        $old_image = $request->old_image;

        if ($request->file('category_image')) {
            $image = $request->file('category_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save('upload/category/' . $name_gen);
            $save_url = 'upload/category/' . $name_gen;

            if (file_exists($old_image)) {
                unlink($old_image);
            }

            Category::findOrFail($id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'category_image' =>  $save_url
            ]);

            $notification = array(
                'message' => 'Category updated with image successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        } else {
            Category::findOrFail($id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
            ]);

            $notification = array(
                'message' => 'Category updated without image successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $old_image = $category->category_image;
        unlink($old_image);
        $category->delete();

        $notification = array(
            'message' => 'Category delete successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
