@extends('layouts.admin')
@section('title')
    Sub Category
@endsection
@section('content')
    <div class="row">
        <div class="col-3">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <!-- Left side: Service Category name -->
                                <div class="ms-3 align-self-center">
                                    <h4 class="mb-0 fs-5"> Category</h4>
                                    <span class="text-muted">{{ $category->name }}</span>
                                </div>
                                <!-- Right side: Service Icon (Gear Icon) -->
                                <div class="ms-auto align-self-center">
                                    <i class="fas fa-tools  fs-4 text-primary"></i> <!-- Gear Icon for Service -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-9">
            <form method="POST"
                action="{{ Route::is('admin.categories.sub_categories.create', ['category' => $category]) ? route('admin.categories.sub_categories.store', ['category' => $category->id]) : route('admin.categories.sub_categories.update', ['sub_category' => $sub_category->id, 'category' => $category]) }}"
                enctype="multipart/form-data" autocomplete="off" id="sub_categories-form">
                @csrf
                {{ Route::is('admin.categories.sub_categories.create', ['category' => $category]) ? '' : method_field('PUT') }}
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-header">
                                <h5>{{ Route::is('admin.categories.sub_categories.create') ? 'Create' : 'Edit' }}
                                    Sub
                                    Category</h5>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label col-form-label">Sub Category Name <sup
                                                class="text-danger">*</sup>
                                        </label>
                                        <input type="text" class="form-control" placeholder="Enter Sub Category Name"
                                            name="name" id="name"
                                            value="{{ isset($sub_category) ? $sub_category->name : '' }}" />
                                        <div id="name-error" style="color:red"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label col-form-label">
                                            Slug
                                            <sup class="text-danger">*</sup>
                                        </label>
                                        <input type="text" class="form-control" id="slug" placeholder="Enter slug"
                                            name="slug" value="{{ isset($sub_category) ? $sub_category->slug : '' }}" />
                                        <div id="slug-error" style="color:red"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                    &nbsp;
                                    <i class="ti ti-device-floppy"></i>
                                </button>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('admin.categories.sub_categories.index', ['category' => $category]) }}"
                                    type="button" class="btn btn-secondary">
                                    Cancel
                                    &nbsp;
                                    <i class="ti ti-arrow-back-up-double"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


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

        $('#sub_categories-form').submit(function(e) {
            e.preventDefault();
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
                            window.location.href = "{!! route('admin.categories.sub_categories.index', ['category' => $category]) !!}";
                        }, 100);
                    } else {
                        toastr.error('There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
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
