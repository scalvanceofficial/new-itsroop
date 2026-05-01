@extends('layouts.admin')
@section('title')
    Video
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.videos.create') ? route('admin.videos.store') : route('admin.videos.update', ['video' => $video->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="videoForm">
        @csrf
        {{ Route::is('admin.videos.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.videos.create') ? 'Create' : 'Edit' }} Video </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">URL

                                </label>
                                <input type="text" class="form-control" placeholder="URL" name="url"
                                    value="{{ isset($video) ? $video->url : '' }}" />
                                <div id="url-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6" id="single-video-field">
                                <label class="control-label col-form-label text-danger">Video (MP4, WEBM | Maximum 25MB)
                                    <sup class="tcul-star-restrict">*</sup>
                                </label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <fieldset class="form-group mb-2">
                                            <input type="file" name="video" class="form-control" id="video-file"
                                                placeholder="Please Select Video" accept="video/*">
                                            <div id="video-error" style="color:red"></div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        @if (isset($video) && $video->video)
                                            @php
                                                $file_extension = pathinfo($video->video, PATHINFO_EXTENSION);
                                                $mime_type =
                                                    $file_extension === 'mp4'
                                                        ? 'video/mp4'
                                                        : ($file_extension === 'webm'
                                                            ? 'video/webm'
                                                            : '');
                                            @endphp
                                            <div id="main-video-container">
                                                <video width="150" height="100" controls>
                                                    <source src="{{ asset(Storage::url($video->video)) }}"
                                                        type="{{ $mime_type }}">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        @endif
                                    </div>
                                </div>
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
                        <a href="{{ route('admin.videos.index') }}" type="button" class="btn btn-secondary">
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
        $('#videoForm').submit(function(e) {
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
                            window.location.href = "{!! route('admin.videos.index') !!}";
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
