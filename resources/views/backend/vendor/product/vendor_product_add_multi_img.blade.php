@extends('vendor.vendor_dashboard')
@section('vendor')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        {{--   Update Main Thumbnail     --}}
        <h6 class="mb-0 text-uppercase">Add Multiple Image</h6>
        <hr>
        <div class="card">
            <form action="{{ route('vendor.store.product.multiimg') }}" method="post" id="myForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $products->id }}">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Choose Multi Image</label>
                        <input class="form-control" type="file" name="multi_img[]" id="multiImg" multiple="">
                        <div class="row mt-2" id="preview_img"></div>
                    </div>
                    <button type="submit" id="Login" class="btn btn-primary px-4">Save Image</button>
                </div>
            </form>
        </div>
    </div>

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
@endsection
