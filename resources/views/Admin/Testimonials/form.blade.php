@extends('layouts.admin')
@section('title')
    testimonials
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.testimonials.create') ? route('admin.testimonials.store') : route('admin.testimonials.update', ['testimonial' => $testimonial->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="testimonial-form">
        @csrf
        {{ Route::is('admin.testimonials.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.testimonials.create') ? 'Create' : 'Edit' }} Testimonial</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label col-form-label">
                                    Name <sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="Enter Customer Name here"
                                    value="{{ isset($testimonial) ? $testimonial->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-form-label">
                                    Title <sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" name="title"
                                    placeholder="Enter title name here"
                                    value="{{ isset($testimonial) ? $testimonial->title : '' }}" />
                                <div id="title-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="control-label col-form-label">
                                    Purchase Item <sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" name="purchase_item"
                                    placeholder="Enter purchase item here"
                                    value="{{ isset($testimonial) ? $testimonial->purchase_item : '' }}" />
                                <div id="purchase_item-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="testimonial-image" class="control-label col-form-label">
                                    Image
                                </label>
                                <div>
                                    <fieldset class="form-group">
                                        <input type="file" name="image" class="form-control" id="testimonial-image"
                                            placeholder="Please Select Image" accept="image/*">
                                        <div id="image-error" style="color:red"></div>
                                    </fieldset>
                                    @if (isset($testimonial))
                                        <div class="mt-3">
                                            <img src="{{ asset(Storage::url($testimonial->image)) }}" alt="Uploaded Image"
                                                class="img-fluid img-thumbnail rounded"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="description" class="control-label col-form-label">Description<sup
                                            class="tcul-star-restrict text-danger">*</sup></label>
                                    <textarea name="description" class="form-control" rows="3" id="description" placeholder="Enter description here">{{ isset($testimonial) ? $testimonial->description : '' }}</textarea>
                                    <div id="description-error" style="color:red"></div>
                                </fieldset>
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
                        <a href="{{ route('admin.testimonials.index') }}" type="button" class="btn btn-secondary">
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
        $('#testimonial-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.testimonials.index') !!}";
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
