@extends('layouts.admin')
@section('title')
    Slider
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.sliders.create') ? route('admin.sliders.store') : route('admin.sliders.update', ['slider' => $slider->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="slider-form">
        @csrf
        {{ Route::is('admin.sliders.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.sliders.create') ? 'Create' : 'Edit' }} Slider </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Enter title" name="title"
                                    value="{{ isset($slider) ? $slider->title : '' }}" />
                                <div id="title-error" style="color:red"></div>
                            </div>
                            {{-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Subtitle</label>

                                <input type="text" class="form-control" placeholder="Enter Subtitle" name="subtitle"
                                    value="{{ isset($slider) ? $slider->subtitle : '' }}" />

                                <div id="subtitle-error" style="color:red"></div>
                            </div> --}}
                            <div class="col-sm-12 {{ Route::is('admin.sliders.create') ? 'col-md-6' : 'col-md-4' }}">
                                <label class="control-label col-form-label">Desktop Image
                                    <sup class="text-danger">
                                        * (Any size image can be uploaded; it will be automatically fitted on the frontend)
                                    </sup>
                                </label>
                                <fieldset class="form-group mb-2">
                                    <input type="file" name="image" class="form-control" id="slider-image"
                                        placeholder="Please Select image"
                                        accept="image/png, image/jpeg, image/jpg,image/webp">
                                    <div id="image-error" style="color:red"></div>
                                </fieldset>
                            </div>
                            @if (isset($slider->image))
                                <div class="col-sm-12 col-md-2 mt-3">
                                    <a href="{{ asset(Storage::url($slider->image)) }}" target="_blank">
                                        <img src="{{ asset(Storage::url($slider->image)) }}" width="150" height="150"
                                            class="img-rounded img-thumbnail">
                                    </a>
                                </div>
                            @endif
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
                        <a href="{{ route('admin.sliders.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
    <script>
        $('#slider-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.sliders.index') !!}";
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
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                                .offset().top - 200
                        }, 500);
                    }
                }
            });
        });
    </script>
@endsection
