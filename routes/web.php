<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\Admin\BrandController;
use App\Http\Controllers\Backend\Admin\CategoryController;
use App\Http\Controllers\Backend\Admin\ProductController;
use App\Http\Controllers\Backend\Admin\SubCategoryController;
use App\Http\Controllers\Backend\Vendor\VendorProductController;
use App\Http\Controllers\Backend\Admin\SliderController;
use App\Http\Controllers\Backend\Admin\BannerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard');
    Route::get('/user/logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::post('/user/profile/store', [UserController::class, 'userProfileStore'])->name('user.profile.store');
    Route::post('/update/password/user', [UserController::class, 'updatePasswordUser'])->name('update.password.user');
}); // Group middleware end

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Dashboard
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'adminUpdatePassword'])->name('update.password');

    // Brand Route
    Route::controller(BrandController::class)->group(function (){
        Route::get('/all/brand', 'allBrand')->name('all.brand');
        Route::get('/add/brand', 'addBrand')->name('add.brand');
        Route::get('/edit/brand/{id}', 'editBrand')->name('edit.brand');
        Route::get('/delete/brand/{id}', 'deleteBrand')->name('delete.brand');
        Route::post('/store/brand', 'brandStore')->name('brand.store');
        Route::post('/update/brand', 'updateBrand')->name('update.brand');
    });

    // Category Route
    Route::controller(CategoryController::class)->group(function (){
        Route::get('/all/category', 'allCategory')->name('all.category');
        Route::get('/add/category', 'addCategory')->name('add.category');
        Route::get('/edit/category/{id}', 'editCategory')->name('edit.category');
        Route::get('/delete/category/{id}', 'deleteCategory')->name('delete.category');
        Route::post('/store/category', 'storeCategory')->name('category.store');
        Route::post('/update/category', 'updateCategory')->name('update.category');
    });

    // SubCategory Route
    Route::controller(SubCategoryController::class)->group(function (){
        Route::get('/all/subcategory', 'allSubCategory')->name('all.subcategory');
        Route::get('/add/subcategory', 'addSubCategory')->name('add.subcategory');
        Route::get('/edit/subcategory/{id}', 'editSubCategory')->name('edit.subcategory');
        Route::get('/delete/subcategory/{id}', 'deleteSubCategory')->name('delete.subcategory');
        Route::get('/subcategory/ajax/{id}', 'ajaxSubCategory')->name('ajax.subcategory');
        Route::post('/store/subcategory', 'storeSubCategory')->name('subcategory.store');
        Route::post('/update/subcategory', 'updateSubCategory')->name('update.subcategory');
    });

    // Inactive and Active Vendor
    Route::controller(AdminController::class)->group(function (){
        Route::get('/inactive/vendor', 'inactiveVendor')->name('inactive.vendor');
        Route::get('/active/vendor', 'activeVendor')->name('active.vendor');
        Route::get('/inactive/vendor/details/{id}', 'inactiveVendorDetails')->name('inactive.vendor.details');
        Route::get('/active/vendor/details/{id}', 'activeVendorDetails')->name('active.vendor.details');
        Route::post('/active/vendor/approve', 'activeVendorApprove')->name('active.vendor.approve');
        Route::post('/inactive/vendor/approve', 'inactiveVendorApprove')->name('inactive.vendor.approve');
    });

    // Product Route
    Route::controller(ProductController::class)->group(function (){
        Route::get('/all/product', 'allProduct')->name('all.product');
        Route::get('/add/product', 'addProduct')->name('add.product');
        Route::get('/add/product/multi-img/{id}', 'addProductMultiImg')->name('add.product.multiimg');
        Route::get('/edit/product/{id}', 'editProduct')->name('edit.product');
        Route::get('/delete/product/{id}', 'deleteProduct')->name('delete.product');
        Route::post('/store/product', 'storeProduct')->name('product.store');
        Route::post('/store/product/multi-img', 'storeProductMultiImg')->name('store.product.multiimg');
        Route::post('/update/product', 'updateProduct')->name('update.product');
        Route::post('/update/product/thumbnail', 'updateProductThumbnail')->name('update.product.thumbnail');
        Route::post('/update/product/multi-image', 'updateProductMultiImage')->name('update.product.multiimage');
        Route::get('/delete/product/multi-image/{id}', 'deleteProductMultiImage')->name('product.multiimg.delete');
        Route::get('/product/inactive/{id}', 'productInactive')->name('product.inactive');
        Route::get('/product/active/{id}', 'productActive')->name('product.active');
    });

    // Slider Controller
    Route::controller(SliderController::class)->group(function (){
        Route::get('/all/slider', 'allSlider')->name('all.slider');
        Route::get('/add/slider', 'addSlider')->name('add.slider');
        Route::get('/edit/slider/{id}', 'editSlider')->name('edit.slider');
        Route::get('/delete/slider/{id}', 'deleteSlider')->name('delete.slider');
        Route::post('/store/slider', 'storeSlider')->name('slider.store');
        Route::post('/update/slider', 'updateSlider')->name('update.slider');
    });

    // Banner Controller
    Route::controller(BannerController::class)->group(function (){
       Route::get('/all/banner', 'allBanner')->name('all.banner');
       Route::get('/add/banner', 'addBanner')->name('add.banner');
       Route::get('/edit/banner/{id}', 'editBanner')->name('edit.banner');
       Route::get('/delete/banner/{id}', 'deleteBanner')->name('delete.banner');
       Route::post('/store/banner', 'storeBanner')->name('banner.store');
       Route::post('/update/banner', 'updateBanner')->name('banner.update');
    });

});



// Vendor Dashboard
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'vendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/logout', [VendorController::class, 'vendorLogout'])->name('vendor.logout');
    Route::get('/vendor/profile', [VendorController::class, 'vendorProfile'])->name('vendor.profile');
    Route::post('/vendor/store', [VendorController::class, 'vendorProfileStore'])->name('vendor.profile.store');
    Route::get('/vendor/change/password', [VendorController::class, 'vendorChangePassword'])->name('vendor.change.password');
    Route::post('/vendor/update/password', [VendorController::class, 'vendorUpdatePassword'])->name('vendor.update.password');

    // Product Route
    Route::controller(VendorProductController::class)->group(function (){
        Route::get('/vendor/all/product', 'vendorAllProduct')->name('vendor.all.product');
        Route::get('/vendor/add/product', 'vendorAddProduct')->name('vendor.add.product');
        Route::get('/vendor/edit/product/{id}', 'vendorEditProduct')->name('vendor.edit.product');
        Route::get('/vendor/delete/product/{id}', 'vendorDeleteProduct')->name('vendor.delete.product');
        Route::post('/vendor/store/product', 'vendorStoreProduct')->name('vendor.product.store');
        Route::post('/vendor/update/product', 'vendorUpdateProduct')->name('vendor.update.product');
        Route::post('/vendor/update/product/thumbnail', 'vendorUpdateProductThumbnail')->name('vendor.update.product.thumbnail');
        Route::post('/vendor/update/product/multi-img', 'vendorUpdateProductMultiImg')->name('vendor.update.product.multiimage');
        Route::post('/vendor/store/product/multi-img', 'vendorStoreProductMultiImg')->name('vendor.store.product.multiimg');
        Route::get('/vendor/delete/product/multi-img/{id}', 'vendorProductMultiImgDelete')->name('vendor.product.multiimg.delete');
        Route::get('/vendor/add/product/multi-img/{id}', 'vendorAddProductMultiImg')->name('vendor.add.product.multiimg');
        Route::get('/vendor/product/inactive/{id}', 'vendorProductInactive')->name('vendor.product.inactive');
        Route::get('/vendor/product/active/{id}', 'vendorProductActive')->name('vendor.product.active');

        // Get Data for Subcategory select Product Form
        Route::get('/subcategory/ajax/{id}', 'ajaxSubCategory')->name('ajax.subcategory');
    });


});

Route::get('/admin/login', [AdminController::class, 'adminLogin'])->middleware(RedirectIfAuthenticated::class)->name('admin.login');
Route::get('/vendor/login', [VendorController::class, 'vendorLogin'])->middleware(RedirectIfAuthenticated::class)->name('vendor.login');

Route::get('/become/vendor', [VendorController::class, 'becomeVendor'])->name('become.vendor');
Route::post('/vendor/register', [VendorController::class, 'vendorRegister'])->name('vendor.register');

