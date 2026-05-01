@extends('layouts.admin')
@section('title')
    Categories
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.categories.create') ? route('admin.categories.store') : route('admin.categories.update', ['category' => $category->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="categoriesForm">
        @csrf
        {{ Route::is('admin.categories.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.categories.create') ? 'Create' : 'Edit' }} Category </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">
                                    Name
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" id="name" placeholder="Enter category name"
                                    name="name" value="{{ isset($category) ? $category->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">
                                    Slug
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" id="slug" placeholder="Enter slug"
                                    name="slug" value="{{ isset($category) ? $category->slug : '' }}" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 {{ Route::is('admin.categories.create') ? 'col-md-6' : 'col-md-4' }}">
                                <label class="control-label col-form-label">
                                    Image
                                    <sup class="text-danger">
                                        *(Aspect ratio: 1∶1)
                                    </sup>
                                </label>
                                <fieldset class="form-group mb-2">
                                    <input type="file" name="image" class="form-control" id="category-image"
                                        placeholder="Select Image" accept="image/png, image/jpeg, image/jpg,image/webp">
                                    <div id="image-error" style="color:red"></div>
                                </fieldset>
                            </div>
                            @if (isset($category->image))
                                <div class="col-sm-12 col-md-2 mt-3">
                                    <a href="{{ asset(Storage::url($category->image)) }}" target="_blank">
                                        <img src="{{ asset(Storage::url($category->image)) }}" width="100" height="100"
                                            class="img-rounded img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Properties
                                    <sup class="text-danger">*</sup>
                                </label>
                                <select class="form-control form-select select2" name="property_ids[]" multiple>
                                    @foreach ($properties as $property)
                                        <option value="{{ $property->id }}"
                                            {{ isset($category) && in_array($property->id, $category->property_ids) ? 'selected' : '' }}>
                                            {{ $property->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="property_ids-error" style="color:red"></div>
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
                        <a href="{{ route('admin.categories.index') }}" type="button" class="btn btn-secondary">
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

        $('#categoriesForm').submit(function(e) {
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
                            window.location.href = "{!! route('admin.categories.index') !!}";
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
