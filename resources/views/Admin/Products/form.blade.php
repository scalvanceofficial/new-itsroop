@extends('layouts.admin')
@section('title')
    Products
@endsection
<style>
    .note-editable ul {
        list-style-type: disc !important;
        padding-left: 20px !important;
    }

    .note-editable ol {
        list-style-type: decimal !important;
        padding-left: 20px !important;
    }
</style>

@section('content')
    <link rel="stylesheet" href="/backend/dist/libs/summernote/dist/summernote-lite.min.css">

    <form method="POST"
        action="{{ Route::is('admin.products.create') ? route('admin.products.store') : route('admin.products.update', ['product' => $product->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="productForm">
        @csrf
        {{ Route::is('admin.products.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.products.create') ? 'Create' : 'Edit' }} Product </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>Basic Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter product name" name="name"
                                    value="{{ isset($product) ? $product->name : '' }}" id="name" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Slug
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter slug" name="slug"
                                    value="{{ isset($product) ? $product->slug : '' }}" id="slug" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Tag Line
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter tag line" name="tag_line"
                                    value="{{ isset($product) ? $product->tag_line : '' }}" />
                                <div id="tag_line-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">SKU
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter sku" name="sku"
                                    value="{{ isset($product) ? $product->sku : '' }}" />
                                <div id="sku-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">HSN</label>
                                <input type="text" class="form-control" placeholder="Enter hsn" name="hsn"
                                    value="{{ isset($product) ? $product->hsn : '' }}" />
                                <div id="hsn-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Keywords
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter keywords" name="keywords"
                                    value="{{ isset($product) ? $product->keywords : '' }}" />
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Gender</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option value="">-- Select Gender (optional) --</option>
                                    <option value="Men"
                                        {{ isset($product) && $product->gender == 'Men' ? 'selected' : '' }}>Men
                                    </option>
                                    <option value="Women"
                                        {{ isset($product) && $product->gender == 'Women' ? 'selected' : '' }}>Women
                                    </option>
                                    <option value="Unisex"
                                        {{ isset($product) && $product->gender == 'Unisex' ? 'selected' : '' }}>Unisex
                                    </option>
                                </select>
                                <div id="gender-error" style="color:red"></div>
                            </div>
                            <div class="row">
                                <!-- Select Type -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-form-label">Select Type <sup
                                                class="text-danger"></sup></label>
                                        <select name="video_type" id="video_type" class="form-control">
                                            <option value="">Select Video Type</option>
                                            <option value="video"
                                                {{ isset($product) && $product->video_type == 'video' ? 'selected' : '' }}>
                                                Upload Video</option>
                                            <option value="youtube_link"
                                                {{ isset($product) && $product->video_type == 'youtube_link' ? 'selected' : '' }}>
                                                YouTube Link</option>
                                            <option value="remove_type"
                                                {{ isset($product) && $product->video_type == 'remove_type' ? 'selected' : '' }}>
                                                Remove</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Video Upload Field -->
                                <div class="{{ isset($product) && $product->video_type == 'video' ? 'col-md-4' : 'col-md-6 d-none' }}"
                                    id="video_upload">
                                    <div class="form-group">
                                        <label class="control-label col-form-label" for="video">Upload Video</label>
                                        <input type="file" name="video" id="video" class="form-control"
                                            accept="video/*">
                                        <div id="video-error" style="color:red"></div>
                                    </div>
                                </div>

                                <!-- Video Preview -->
                                @if (isset($product) && $product->video_type == 'video' && !empty($product->video))
                                    <div class="col-md-2 d-none" id="video_preview">
                                        <div class="form-group">
                                            <label>Current Video:</label>
                                            <video width="100%" controls style="max-height: 150px; object-fit: cover;">
                                                <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                @endif

                                <!-- YouTube Link Field -->
                                <div class="col-md-6 d-none" id="storeyoutube_link">
                                    <div class="form-group">
                                        <label class="control-label col-form-label" for="youtube_link">YouTube URL</label>
                                        <input type="text" name="youtube_link" id="youtube_link" class="form-control"
                                            placeholder="Paste YouTube link"
                                            value="{{ isset($product) ? $product->youtube_link : '' }}">
                                        <div id="youtube_link-error" style="color:red"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <textarea type="text" name="description" class="summernote" rows="1" id="description"
                                    placeholder="Enter description">{{ isset($product) ? $product->description : '' }}</textarea>
                                <div id="description-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>Highlights</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <textarea type="text" name="highlights" class="summernote" rows="1" id="highlights"
                                    placeholder="Enter highlights">{{ isset($product) ? $product->highlights : '' }}
                                    </textarea>
                                <div id="highlights-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>Product Variants</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Categories
                                    <sup class="text-danger">*</sup>
                                </label>
                                <select class="form-control form-select select2" name="category_ids[]" id="categoryIds"
                                    multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($product) && in_array($category->id, $product->category_ids) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="category_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Sub Categories
                                   
                                </label>
                                <select class="form-control form-select select2" name="sub_category_ids[]"
                                    id="subCategoryIds" multiple>
                                    @foreach ($sub_categories as $sub_category)
                                        <option value="{{ $sub_category->id }}"
                                            {{ isset($product) && in_array($sub_category->id, $product->sub_category_ids ?? []) ? 'selected' : '' }}>
                                            {{ $sub_category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="sub_category_ids-error" style="color:red"></div>
                            </div>
                        </div>
                        <div id="propertyValues">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <span class="spinner-span" role="status" aria-hidden="true"></span>
                                <span class="save-btn-text">Save</span>
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('admin.products.index') }}" type="button" class="btn btn-secondary">
                                Cancel
                                &nbsp;
                                <i class="ti ti-arrow-back-up-double"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </form>

    <script src="/backend/dist/libs/summernote/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categoryIds').on('change', function() {
                var categoryIds = $(this).val();

                if (categoryIds && categoryIds.length > 0) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.products.sub-categories') }}",
                        data: {
                            category_ids: categoryIds,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(subCategories) {
                            var $subCatSelect = $('#subCategoryIds');
                            $subCatSelect.empty();

                            $.each(subCategories, function(i, subCat) {
                                // console.log(subCat.id, subCat.name);

                                $subCatSelect.append(
                                    $('<option>', {
                                        value: subCat.id,
                                        text: subCat.name
                                    })
                                );
                            });

                            $subCatSelect.trigger('change');
                        },
                        error: function() {
                            toastr.error('Failed to load sub-categories.');
                        }
                    });
                } else {
                    $('#subCategoryIds').empty();
                }
            });
        });



        $(document).ready(function() {
            $('#name').on('input', function() {
                var slug = $(this).val();
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-z0-9 -]/g, '');
                slug = slug.replace(/\s+/g, '-');
                slug = slug.replace(/-+/g, '-');
                $('#slug').val(slug);
            });

            @if (isset($product))
                let categoryIds = @json($product->category_ids);
                let productId = @json($product->id);
                getPropertyValues(categoryIds, productId);
            @endif

            $('#categoryIds').change(function(e) {
                let categoryIds = $(this).val();
                let productId = null;

                if (Array.isArray(categoryIds) && categoryIds.length > 0) {
                    getPropertyValues(categoryIds, productId);
                }
            });


            $('#productForm').submit(function(e) {
                e.preventDefault();
                $('#submit-btn').attr('disabled', true)
                $('.spinner-span').addClass('spinner-border spinner-border-sm')
                $('div[id$="-error"]').empty();
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            setTimeout(function() {
                                window.location.href =
                                    "{!! route('admin.products.index') !!}";
                            }, 100);
                        } else {
                            $('#submit-btn').attr('disabled', false);
                            $('.spinner-span').removeClass(
                                'spinner-border spinner-border-sm')
                            toastr.error('There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass(
                            'spinner-border spinner-border-sm')
                        toastr.error(
                            'There are some errors in Form. Please check your inputs',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr
                                        .responseJSON.errors)[
                                        0] +
                                    '-error')
                                .offset().top - 200
                        }, 500);
                    }
                });
            });

            $(".summernote").summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: false,
            });

            $(".inline-editor").summernote({
                airMode: true,
            });

            (window.edit = function() {
                $(".click2edit").summernote();
            }),

            (window.save = function() {
                $(".click2edit").summernote("destroy");
            });

            var edit = function() {
                $(".click2edit").summernote({
                    focus: true
                });
            };

            var save = function() {
                var markup = $(".click2edit").summernote("code");
                $(".click2edit").summernote("destroy");
            };

            $(".airmode-summer").summernote({
                airMode: true,
            });
        })

        function getPropertyValues(categoryIds, productId) {
            $.ajax({
                type: "POST",
                url: '{!! route('admin.products.property-values') !!}',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_ids: categoryIds,
                    product_id: productId
                },
                success: function(data) {
                    $('#propertyValues').html(data);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            function toggleFields() {
                let type = $('#video_type').val();

                // Hide all fields
                $('#video_upload, #storeyoutube_link, #video_preview').addClass('d-none');

                // Show based on selected type
                if (type === 'video') {
                    $('#video_upload').removeClass('d-none');
                    $('#video_preview').removeClass('d-none');
                } else if (type === 'youtube_link') {
                    $('#storeyoutube_link').removeClass('d-none');
                }
            }

            // Initialize on page load
            toggleFields();

            // On change event
            $('#video_type').on('change', function() {
                toggleFields();
            });
        });
    </script>
@endsection
