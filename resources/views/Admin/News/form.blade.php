@extends('layouts.admin')
@section('title')
    News
@endsection
@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <form method="POST"
        action="{{ Route::is('admin.news.create') ? route('admin.news.store') : route('admin.news.update', ['news' => $news->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="news-form">
        @csrf
        {{ Route::is('admin.news.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.news.create') ? 'Create' : 'Edit' }} News</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label col-form-label">
                                    Title <sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Enter title name here" value="{{ isset($news) ? $news->title : '' }}" />
                                <div id="title-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Slug <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Slug here..." name="slug"
                                    id="slug" value="{{ isset($news) ? $news->slug : '' }}" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="news-image" class="control-label col-form-label">
                                    Thumbnail Image<sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <div>
                                    <fieldset class="form-group">
                                        <input type="file" name="thumbnail_image" class="form-control" id="news-image"
                                            placeholder="Please Select Photo" accept="image/*">
                                        <div id="thumbnail_image-error" style="color:red"></div>
                                    </fieldset>
                                    @if (isset($news))
                                        <div class="mt-3">
                                            <img src="{{ asset(Storage::url($news->thumbnail_image)) }}"
                                                alt="Uploaded Image" class="img-fluid img-thumbnail rounded"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="news-image" class="control-label col-form-label">
                                    Main Image<sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <div>
                                    <fieldset class="form-group">
                                        <input type="file" name="main_image" class="form-control" id="news-image"
                                            placeholder="Please Select Photo" accept="image/*">
                                        <div id="main_image-error" style="color:red"></div>
                                    </fieldset>
                                    @if (isset($news))
                                        <div class="mt-3">
                                            <img src="{{ asset(Storage::url($news->main_image)) }}" alt="Uploaded Image"
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
                                    <label for="description" class="control-label col-form-label">Description</label>
                                    <textarea name="description_1" class="form-control cktextarea" rows="3" id="description"
                                        placeholder="Enter description here">{{ isset($news) ? $news->description_1 : '' }}</textarea>
                                    <div id="description_1-error" style="color:red"></div>
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
                        <a href="{{ route('admin.news.index') }}" type="button" class="btn btn-secondary">
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
        let editorInstances = [];
        document.querySelectorAll('.cktextarea').forEach((descriptionBox, index) => {
            ClassicEditor.create(descriptionBox)
                .then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setStyle('line-height', '1.5', editor.editing.view.document.getRoot());
                    });
                    editorInstances[index] = editor; // Store each editor instance
                })
                .catch(error => {
                    console.error(error);
                });
        });


        $('#news-form').submit(function(e) {
            e.preventDefault();
            $('#submit-btn').attr('disabled', true)
            $('.spinner-span').addClass('spinner-border spinner-border-sm')

            $('div[id$="-error"]').empty();
            var form = $(this);
            var url = form.attr('action');
            var formData = new FormData(this);
            $('.cktextarea').each(function(index) {
                var editorData = editorInstances[index].getData();
                formData.append($(this).attr('name'), editorData);
            });

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
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
                            window.location.href = "{!! route('admin.news.index') !!}";
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

        $('#title').keyup(function(e) {
            var name = $(this).val();
            var slug = name.toLowerCase().replace(/ /g, '-');
            $('#slug').val(slug);
        });
    </script>
@endsection
