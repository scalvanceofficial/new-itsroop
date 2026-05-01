@extends('layouts.admin')
@section('title')
    Collections
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.collections.create') ? route('admin.collections.store') : route('admin.collections.update', ['collection' => $collection->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="collectionsForm">
        @csrf
        {{ Route::is('admin.collections.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.collections.create') ? 'Create' : 'Edit' }} Collection </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">
                                    Name
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" id="name" placeholder="Enter name"
                                    name="name" value="{{ isset($collection) ? $collection->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">
                                    Slug
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" id="slug" placeholder="Enter slug"
                                    name="slug" value="{{ isset($collection) ? $collection->slug : '' }}" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Products
                                    <sup class="text-danger">*</sup>
                                </label>
                                <select class="form-control form-select select2" name="product_ids[]" multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ isset($collection) && in_array($product->id, $collection->product_ids) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="product_ids-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="spinner-span" role="status" aria-hidden="true"></span>
                            <span class="save-btn-text">Save</span>
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.collections.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                var slug = $(this).val();
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-z0-9 -]/g, '');
                slug = slug.replace(/\s+/g, '-');
                slug = slug.replace(/-+/g, '-');
                $('#slug').val(slug);
            });
        });

        $('#collectionsForm').submit(function(e) {
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
                            window.location.href = "{!! route('admin.collections.index') !!}";
                        }, 100);
                    } else {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm')
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
                    $('.spinner-span').removeClass('spinner-border spinner-border-sm')
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '-error').html(value);
                    });
                    $('html, body').animate({
                        scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                            .offset().top - 200
                    }, 500);
                }
            });
        });
    </script>
@endsection
