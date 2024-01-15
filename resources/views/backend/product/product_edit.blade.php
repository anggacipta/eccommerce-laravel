@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">eCommerce</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Product</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">Edit Product</h5>
                <hr/>
                <form action="{{ route('update.product') }}" method="post" id="myForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $products->id }}">
                    <div class="form-body mt-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="border border-3 p-4 rounded">
                                    <div class="form-group mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="inputProductTitle" name="product_name" placeholder="Enter product title" value="{{ $products->product_name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Tags</label>
                                        <input type="text" name="product_tags" class="form-control visually-hidden" data-role="tagsinput" value="{{ $products->product_tags }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Size</label>
                                        <input type="text" name="product_size" class="form-control visually-hidden" data-role="tagsinput" value="{{ $products->product_size }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Color</label>
                                        <input type="text" name="product_color" class="form-control visually-hidden" data-role="tagsinput" value="{{ $products->product_color }}">
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="inputProductDescription" class="form-label">Short Description</label>
                                        <textarea class="form-control" id="inputProductDescription" name="short_desc" rows="3">{{ $products->short_desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputProductDescription" class="form-label">Long Description</label>
                                        <textarea id="mytextarea" name="long_desc">{!! $products->long_desc !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="border border-3 p-4 rounded">
                                    <div class="row g-3">
                                        <div class="col-md-6 form-group">
                                            <label for="inputPrice" class="form-label">Product Price</label>
                                            <input type="text" class="form-control" name="selling_price" id="inputPrice" placeholder="00.00" value="{{ $products->selling_price }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputCompareatprice" class="form-label">Discount Price</label>
                                            <input type="text" name="discount_price" class="form-control" id="inputCompareatprice" placeholder="00.00" value="{{ $products->discount_price }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="inputCostPerPrice" class="form-label">Product Code</label>
                                            <input type="text" name="product_code" class="form-control" id="inputCostPerPrice" placeholder="00.00" value="{{ $products->product_code }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="inputStarPoints" class="form-label">Product Quantity</label>
                                            <input type="text" name="product_qty" class="form-control" id="inputStarPoints" placeholder="00.00" value="{{ $products->product_qty }}">
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="inputProductType" class="form-label">Product Brand</label>
                                            <select name="brand_id" class="form-select" id="inputProductType">
                                                <option></option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ $brand->id == $products->brand_id ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="inputVendor" class="form-label">Product Category</label>
                                            <select name="category_id" class="form-select" id="inputVendor">
                                                <option></option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $category->id == $products->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="inputCollection" class="form-label">Product SubCategory</label>
                                            <select name="subcategory_id" class="form-select" id="inputCollection">
                                                @foreach($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}" {{ $subcategory->id == $products->subcategory_id ? 'selected' : '' }}>{{ $subcategory->subcategory_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputVendor" class="form-label">Select Vendor</label>
                                            <select name="vendor_id" class="form-select" id="inputVendor">
                                                <option>Select Vendor</option>
                                                @foreach( $active_vendor as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ $vendor->id == $products->vendor_id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--   Section Offers   --}}
                                        <div class="col-12">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="hot_deals" type="checkbox" value="1" id="flexCheckDefault" {{ $products->hot_deals == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault">Hot Deals</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="featured" type="checkbox" value="1" id="flexCheckDefault" {{ $products->featured == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault">Featured</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="special_offer" type="checkbox" value="1" id="flexCheckDefault" {{ $products->special_offer == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault">Special Offer</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="special_deals" type="checkbox" value="1" id="flexCheckDefault" {{ $products->special_deals == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault">Special Deals</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" id="Login" class="btn btn-primary px-4">Edit Product</button>
                                                <img src="https://media.tenor.com/J7nXdDCdmLcAAAAi/sky-spinning.gif" height="80" width="120" class="my-3 hidden-img">
                                                <button type="button" class="btn btn-sm btn-danger hidden-img" id="close">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end row-->
                    </div>
                </form>
            </div>
        </div>

        {{--   Update Main Thumbnail     --}}
        <h6 class="mb-0 text-uppercase">Update Image Thumbnail</h6>
        <hr>
        <div class="card">
            <form action="{{ route('update.product.thumbnail') }}" method="post" id="myForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $products->id }}">
                <input type="hidden" name="old_image" value="{{ $products->product_thumbnail }}">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Choose Thumbnail Image</label>
                        <input class="form-control" type="file" name="product_thumbnail" id="formFile">
                    </div>
                    <div class="mb-3">
                        <img src="{{ asset($products->product_thumbnail) }}" alt="" style="width: 100px; height: 100px;">
                    </div>
                    <button type="submit" id="Login" class="btn btn-primary px-4">Edit Image</button>
                </div>
            </form>
        </div>

        {{--   Update Multiple Image     --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Update Multi Image</div>
            <div class="ms-auto">
                <a href="{{ route("add.product.multiimg", $products->id) }}" class="btn btn-success">Add Multiple Image</a>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                <table class="table mb-0 table-striped">
                    <thead>
                    <tr>
                        <th scope="col">S1</th>
                        <th scope="col">Image</th>
                        <th scope="col">Change Image</th>
                        <th scope="col">Update Image</th>
                        <th scope="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <form action="{{ route('update.product.multiimage') }}" method="post" id="myForm" enctype="multipart/form-data">
                        @csrf
                        @foreach($multi_img as $key => $img)
                        <tr>
                            <th scope="row">{{ $key+1 }}</th>
                            <td><img src="{{ asset($img->photo_name) }}" style="width: 70px; height: 70px"></td>
                            <td><input type="file" class="form-group" name="multi_img[{{$img->id}}]"></td>
                            <td><button type="submit" class="btn btn-primary">Update Image</button></td>
                            <td>
                                <a href="{{ route('product.multiimg.delete', $img->id) }}" class="btn btn-danger" id="delete">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div> {{--  End Page Content  --}}




    {{--  Multi Image Script  --}}
    <script>
        $(document).ready(function(){
            $('#multiImg').on('change', function(){ //on file input change
                if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
                {
                    var data = $(this)[0].files; //this file data

                    $.each(data, function(index, file){ //loop though each file
                        if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                            var fRead = new FileReader(); //new filereader
                            fRead.onload = (function(file){ //trigger function on successful read
                                return function(e) {
                                    var img = $('<img/>').addClass('thumb').attr('src', e.target.result) .width(100)
                                        .height(80); //create image element
                                    $('#preview_img').append(img); //append image to output element
                                };
                            })(file);
                            fRead.readAsDataURL(file); //URL representing the file's data.
                        }
                    });

                }else{
                    alert("Your browser doesn't support File API!"); //if File API is absent
                }
            });
        });
    </script>

    {{--  Thumbnail Image Script  --}}
    <script type="text/javascript">
        function mainThanURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#mainThumb').attr('src', e.target.result).width(80).height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    {{--  Get Subcategory Choice Script  --}}
    <script type="text/javascript">
        $(document).ready(function (){
            $("select[name = 'category_id']").on('change', function (){
                var category_id = $(this).val();
                if (category_id) {
                    $.ajax({
                        url: "{{ url('/subcategory/ajax') }}/" + category_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $("select[name = 'subcategory_id']").html('');
                            var d = $("select[name = 'subcategory_id']").empty();
                            $.each(data, function (key, value){
                                $("select[name = 'subcategory_id']").append('<option value=" '+ value.id + '" >' + value.subcategory_name + '</option>')
                            })
                        },
                    });
                } else {
                    alert('danger');
                }
            })
        })
    </script>

    {{--  Validation Script  --}}
    <script type="text/javascript">
        $(document).ready(function (){
            $('#myForm').validate({
                rules: {
                    product_name: {
                        required: true,
                    },
                    short_desc: {
                        required: true,
                    },
                    product_thumbnail: {
                        required: true,
                    },
                    selling_price: {
                        required: true,
                    },
                    multi_img: {
                        required: true,
                    },
                    product_code: {
                        required: true,
                    },
                    product_qty: {
                        required: true,
                    },
                    brand_id: {
                        required: true,
                    },
                    category_id: {
                        required: true,
                    },
                    subcategory_id: {
                        required: true,
                    },
                },
                messages : {
                    product_name: {
                        required : 'Please Enter Product Name',
                    },
                    short_desc: {
                        required : 'Please Enter Short Description',
                    },
                    product_thumbnail: {
                        required : 'Please Select Product Thumbnail',
                    },
                    selling_price: {
                        required : 'Please Enter Selling Price',
                    },
                    multi_img: {
                        required : 'Please Select Multi Img',
                    },
                    product_code: {
                        required : 'Please Enter Product Code',
                    },
                    product_qty: {
                        required : 'Please Enter Product Qty',
                    },
                },
                errorElement : 'span',
                errorPlacement : function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight : function(element, errorClass, validClass){
                    $(element).addClass('is-invalid');
                },
                unhighlight : function(element, errorClass, validClass){
                    $(element).removeClass('is-invalid');
                },
            })
        })
    </script>

    {{--  Add Loading Gif  --}}
    <script type="text/javascript">
        var button = document.getElementById('Login');
        var buttonClose = document.getElementById('close');
        var pleaseWait = document.querySelector(".hidden-img");

        button.addEventListener("click", function(evt){

            // Show the message by removing the class that hides it:
            pleaseWait.classList.remove("hidden-img");
            buttonClose.classList.remove("hidden-img");

            setTimeout(function(){
                pleaseWait.classList.add("hidden");
                special.classList.remove("hidden");
            }, 2000);
        });

        buttonClose.addEventListener("click", function (evt){
            pleaseWait.classList.add("hidden-img");
            buttonClose.classList.add("hidden-img");
        })
    </script>

@endsection
