@extends('layouts.admin')
@section('title')
    Properties Management
@endsection

@section('content')
    <form method="POST"
        action="{{ Route::is('admin.properties.create') ? route('admin.properties.store') : route('admin.properties.update', ['property' => $property->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="propertiesForm">

        @csrf
        {{ Route::is('admin.properties.create') ? '' : method_field('PUT') }}

        <!-- ================= PROPERTY DETAILS ================= -->
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">

                    <div class="card-header">
                        <h5>{{ Route::is('admin.properties.create') ? 'Create' : 'Edit' }} Property</h5>
                    </div>

                    <div class="card-body border-top">
                        <div class="row mt-4">

                            <!-- NAME -->
                            <div class="col-md-4">
                                <label>Name *</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $property->name ?? '' }}">
                            </div>

                            <!-- LABEL -->
                            <div class="col-md-4">
                                <label>Label</label>
                                <input type="text" class="form-control" name="label"
                                    value="{{ $property->label ?? '' }}">
                            </div>

                            <!-- IS COLOR -->
                            <div class="col-md-4">
                                <label>Is Color *</label>
                                <select class="form-control" name="is_color" id="isColor">
                                    <option value="YES"
                                        {{ isset($property) && $property->is_color == 'YES' ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="NO"
                                        {{ isset($property) && $property->is_color == 'NO' ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>

                            <!-- PROPERTY TYPE -->
                            <div class="col-md-4 mt-3">
                                <label>Property Type *</label>
                                <select class="form-control" id="propertyType" name="type">
                                    <option value="general">General</option>
                                    <option value="shirt_size">Shirt Size</option>
                                    <option value="shoe_size">Shoe Size</option>
                                    <option value="color">Color</option>
                                </select>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label>Sex</label>
                                <select class="form-control" name="sex">
                                    <option value="">Select</option>
                                    <option value="Male"
                                        {{ isset($property) && $property->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female"
                                        {{ isset($property) && $property->sex == 'Female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="Unisex"
                                        {{ isset($property) && $property->sex == 'Unisex' ? 'selected' : '' }}>Unisex
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= PROPERTY VALUES ================= -->
        <div class="row mt-3">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">

                    <div class="card-header d-flex justify-content-between">
                        <h5>Property Values</h5>
                        <button type="button" class="btn btn-primary" id="addValueBtn">
                            Add Value <i class="fa fa-plus"></i>
                        </button>
                    </div>

                    <div class="card-body border-top" id="createValuesBody">
                        <!-- dynamic values -->
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Save <i class="ti ti-device-floppy"></i>
                        </button>

                        <a href="{{ route('admin.properties.index') }}" class="btn btn-danger">
                            Cancel
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </form>

    <!-- ================= SCRIPT ================= -->

    <script>
        $(document).ready(function() {

            // ADD VALUE
            $('#addValueBtn').click(function() {
                createValue();
            });

            // REMOVE ROW
            $(document).on('click', '.removeValueBtn', function() {
                $(this).closest('.valueRow').remove();
            });

            // ================= AJAX SUBMIT =================
            $('#propertiesForm').submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let btn = $('#submitBtn');

                btn.prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    processData: false,

                    success: function(res) {
                        btn.prop('disabled', false);

                        if (res.status === 'success') {
                            toastr.success(res.message);

                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('admin.properties.index') }}";
                            }, 500);
                        } else {
                            toastr.error('Something went wrong');
                        }
                    },

                    error: function(xhr) {
                        btn.prop('disabled', false);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, val) {
                                toastr.error(val[0]);
                            });
                        } else {
                            toastr.error('Server Error');
                        }
                    }
                });
            });

        });


        // ================= CREATE VALUE =================
        function createValue() {

            let type = $('#propertyType').val();
            let is_color = $('#isColor').val();
            let id = Math.floor(Math.random() * 100000);

            let valueField = '';

            // SHIRT SIZE
            if (type === 'shirt_size') {
                valueField = `
            <select name="names[${id}]" class="form-control" required>
                <option value="">Select Size</option>
                <option>S</option>
                <option>M</option>
                <option>L</option>
                <option>XL</option>
                <option>XXL</option>
            </select>
        `;
            }

            // SHOE SIZE
            else if (type === 'shoe_size') {
                valueField = `
            <select name="names[${id}]" class="form-control" required>
                <option value="">Select Size</option>
                ${[5,6,7,8,9,10,11,12].map(s => `<option>${s}</option>`).join('')}
            </select>
        `;
            }

            // COLOR
            else if (type === 'color') {
                valueField = `
            <input type="text" name="names[${id}]" class="form-control" placeholder="Color Name" required>
        `;
            }

            // GENERAL
            else {
                valueField = `
            <input type="text" name="names[${id}]" class="form-control" placeholder="Enter Value" required>
        `;
            }

            let colorField = '';

            if (is_color === 'YES') {
                colorField = `
            <div class="col-md-3">
                <input type="color" name="colors[${id}]" class="form-control form-control-color">
            </div>
        `;
            } else {
                colorField = `<div class="col-md-3"></div>`;
            }

            let html = `
        <div class="row valueRow mt-2">
            <div class="col-md-12">
                <div style="border:2px solid #5d87ff; padding:10px; border-radius:10px;">
                    <div class="row">

                        <div class="col-md-4">
                            ${valueField}
                        </div>

                        ${colorField}

                        <div class="col-md-2">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox"
                                    name="statuses[${id}]" value="ACTIVE" checked>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input type="number" name="indexes[${id}]"
                                class="form-control" placeholder="Index" required>
                        </div>

                        <div class="col-md-1 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger removeValueBtn">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    `;

            $('#createValuesBody').prepend(html);
        }
    </script>
@endsection
