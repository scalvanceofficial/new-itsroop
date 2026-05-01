@extends('layouts.frontend')
@section('title')
    Address | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Address</div>
        </div>
    </div>
    <!-- /page-title -->

    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('layouts.frontend.my-account-sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-address">
                        <div class=" widget-inner-address">
                            <div class="text-center">
                                <button class="tf-btn btn-fill animate-hover-btn btn-address mb_20">Add a new
                                    address</button>

                            </div>
                            <form class="show-form-address wd-form-address" id="addressForm"
                                action="{{ route('frontend.addresses.store') }}">
                                @csrf
                                <div class="title text-center">Add a new address</div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="box-field">
                                            <div class="tf-field style-1">
                                                <input class="tf-field-input tf-input" placeholder=" " type="text"
                                                    name="recipient_first_name" id="recipient_first_name_input">
                                                <label class="tf-field-label" for="recipient_first_name_input">First
                                                    name</label>
                                                <span id="recipient_first_name_error" style="color:red"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="box-field">
                                            <div class="tf-field style-1">
                                                <input class="tf-field-input tf-input" placeholder=" " type="text"
                                                    name="recipient_last_name" id="recipient_last_name_input">
                                                <label class="tf-field-label" for="recipient_last_name_input">Last
                                                    name</label>
                                                <span id="recipient_last_name_error" style="color:red"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="select-custom">
                                        <select class="tf-select w-100" id="type" name="type">
                                            <option value="">Address Type</option>
                                            <option value="Home">Home</option>
                                            <option value="Office">Office</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <span id="type_error" style="color:red;"></span>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1 text-start">
                                        <input class="tf-field-input tf-input" name="address_line_1" type="text"
                                            id="address_line_1" name="address_line_1">
                                        <label class="tf-field-label fw-4 text_black-2" for="address_line_1">Address
                                            Line
                                            1</label>
                                        <span id="address_line_1_error" style="color:red;"></span>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1 text-start">
                                        <input class="tf-field-input tf-input" name="address_line_2" type="text"
                                            id="address_line_2" name="address_line_2">
                                        <label class="tf-field-label fw-4 text_black-2" for="address_line_2">Address
                                            Line
                                            2</label>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1 text-start">
                                        <input class="tf-field-input tf-input" name="city" type="text" id="city"
                                            name="city">
                                        <label class="tf-field-label fw-4 text_black-2" for="city">City</label>
                                        <span id="city_error" style="color:red;"></span>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1 text-start">
                                        <input class="tf-field-input tf-input" name="pincode" type="text"
                                            id="AddressZipNew" name="AddressZipNew">
                                        <label class="tf-field-label fw-4 text_black-2" for="AddressZipNew">
                                            Postal/ZIP Code
                                        </label>
                                        <span id="pincode_error" style="color:red;"></span>
                                    </div>
                                </div>
                                <div class="box-field text-start">
                                    <div class="box-checkbox fieldset-radio d-flex align-items-center gap-8">
                                        <input type="checkbox" id="check-new-address" name="default" class="tf-check"
                                            value="YES">
                                        <label for="check-new-address" class="text_black-2 fw-4">Set as default
                                            address</a>.</label>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-20">
                                    <button type="submit" class="tf-btn btn-fill animate-hover-btn">Add
                                        addrssess</button>
                                    <span class="tf-btn btn-fill animate-hover-btn btn-hide-address">Cancel</span>
                                </div>
                            </form>
                            <div class="list-account-address">
                                @foreach ($addresses as $address)
                                    <div class="account-address-item">
                                        <div class="text-center">
                                            <h6 class="mb_20">{{ $address->default == 'YES' ? 'Default' : '' }}
                                                ({{ $address->type }})
                                            </h6>
                                            <p>{{ $address->recipient_first_name }}
                                                {{ $address->recipient_last_name }}</p>
                                            <p>{{ $address->address_line_1 }}</p>
                                            <p>{{ $address->address_line_2 }}</p>
                                            <p>{{ $address->city }}</p>
                                            <p>{{ $address->pincode }}</p>
                                            <p>{{ $address->user->email }}</p>
                                            <p class="mb_10">{{ $address->user->mobile }}</p>
                                        </div>

                                        <div class="d-flex gap-10 justify-content-center">
                                            <button class="tf-btn btn-fill animate-hover-btn btn-edit-address"
                                                data-id="{{ $address->id }}">
                                                <span>Edit</span>
                                            </button>
                                            <button class="tf-btn btn-outline animate-hover-btn btn-delete-address"
                                                data-id="{{ $address->id }}">
                                                <span>Delete</span>
                                            </button>
                                        </div>

                                        <!-- Edit Form (Hidden by Default) -->
                                        <form class="edit-form-address wd-form-address d-none"
                                            id="editAddressForm{{ $address->id }}">
                                            <div class="title">Edit address</div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box-field">
                                                        <div class="tf-field style-1">
                                                            <input class="tf-field-input tf-input first-name-input"
                                                                placeholder=" " type="text"
                                                                name="recipient_first_name"
                                                                id="recipient_first_name{{ $address->id }}"
                                                                value="{{ $address->recipient_first_name }}">
                                                            <label class="tf-field-label"
                                                                for="recipient_first_name_input">First
                                                                name</label>
                                                            <span id="recipient_first_name_error_{{ $address->id }}"
                                                                style="color:red"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="box-field">
                                                        <div class="tf-field style-1">
                                                            <input class="tf-field-input tf-input last-name-input"
                                                                placeholder=" " type="text" name="recipient_last_name"
                                                                id="recipient_last_name{{ $address->id }}"
                                                                value="{{ $address->recipient_last_name }}">
                                                            <label class="tf-field-label"
                                                                for="recipient_last_name_input">Last
                                                                name</label>
                                                            <span id="recipient_last_name_error_{{ $address->id }}"
                                                                style="color:red"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-field">
                                                <div class="select-custom">
                                                    <select class="tf-select w-100 select-type" id="type"
                                                        name="type">
                                                        <option value="">Address Type</option>
                                                        <option value="Home"
                                                            {{ $address->type == 'Home' ? 'selected' : '' }}>Home</option>
                                                        <option value="Office"
                                                            {{ $address->type == 'Office' ? 'selected' : '' }}>Office
                                                        </option>
                                                        <option value="Other"
                                                            {{ $address->type == 'Other' ? 'selected' : '' }}>Other
                                                        </option>
                                                    </select>
                                                    <span id="type_error_{{ $address->id }}" style="color:red;"></span>
                                                </div>
                                            </div>
                                            <div class="box-field">
                                                <div class="tf-field style-1">
                                                    <input class="tf-field-input tf-input address-1-input" type="text"
                                                        name="address_line_1" id="addressEdit{{ $address->id }}"
                                                        value="{{ $address->address_line_1 }}">
                                                    <label class="tf-field-label fw-4 text_black-2"
                                                        for="addressEdit{{ $address->id }}">Address
                                                    </label>
                                                    <span id="address_line_1_error_{{ $address->id }}"
                                                        style="color:red;">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="box-field">
                                                <div class="tf-field style-1">
                                                    <input class="tf-field-input tf-input address-2-input" type="text"
                                                        name="address_line_2" id="addressEdit{{ $address->id }}"
                                                        value="{{ $address->address_line_2 }}">
                                                    <label class="tf-field-label fw-4 text_black-2"
                                                        for="addressEdit{{ $address->id }}">Address
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="box-field">
                                                <div class="tf-field style-1">
                                                    <input class="tf-field-input tf-input city-input" type="text"
                                                        name="city" id="cityEdit{{ $address->id }}"
                                                        value="{{ $address->city }}">
                                                    <label class="tf-field-label fw-4 text_black-2"
                                                        for="cityEdit{{ $address->id }}">City
                                                    </label>
                                                    <span id="city_error_{{ $address->id }}" style="color:red;"></span>
                                                </div>
                                            </div>

                                            <div class="box-field">
                                                <div class="tf-field style-1">
                                                    <input class="tf-field-input tf-input pincode-input" type="text"
                                                        name="pincode" id="pincodeEdit{{ $address->id }}"
                                                        value="{{ $address->pincode }}">
                                                    <label class="tf-field-label fw-4 text_black-2"
                                                        for="pincodeEdit{{ $address->id }}">Postal/ZIP code
                                                    </label>
                                                    <span id="pincode_error_{{ $address->id }}" style="color:red;">
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="box-field text-start">
                                                <div class="box-checkbox fieldset-radio d-flex align-items-center gap-8">
                                                    <input type="checkbox" class="tf-check default-checkbox"
                                                        id="defaultEdit{{ $address->id }}"
                                                        {{ $address->default == 'YES' ? 'checked' : '' }}>
                                                    <label for="defaultEdit{{ $address->id }}"
                                                        class="text_black-2 fw-4">Set as default address
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-center gap-20">
                                                <button type="button"
                                                    class="tf-btn btn-fill animate-hover-btn btn-update-address"
                                                    data-id="{{ $address->id }}">Update address</button>
                                                <span class="tf-btn btn-fill animate-hover-btn btn-hide-edit-address"
                                                    data-id="{{ $address->id }}">Cancel</span>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function(e) {
            // Address Store Form
            $('#addressForm').submit(function(e) {
                e.preventDefault();
                $('#address_line_1_error').empty();
                $('#city_error').empty();
                $('#pincode_error').empty();
                $('#type_error').empty();

                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(
                                response.message,
                                '', {
                                    showMethod: "slideDown",
                                    timeOut: 1000,
                                    closeButton: true,
                                });

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            console.log(key);
                            $('#' + key + '_error').html(value);
                        });
                    }
                });
            });

            // Show Edit Form (Hide Others)
            $(".btn-edit-address").click(function() {
                let addressId = $(this).data("id");
                $(".edit-form-address").addClass("d-none"); // Hide all other forms
                $("#editAddressForm" + addressId).removeClass("d-none"); // Show only the selected form
            });

            // Hide Edit Form
            $(".btn-hide-edit-address").click(function() {
                let addressId = $(this).data("id");
                $("#editAddressForm" + addressId).addClass("d-none");
            });

            // Update Address
            $(".btn-update-address").click(function() {
                let addressId = $(this).data("id");
                let form = $("#editAddressForm" + addressId);

                let firstName = form.find(".first-name-input").val();
                let lastName = form.find(".last-name-input").val();
                let addressLine1 = form.find(".address-1-input").val();
                let addressLine2 = form.find(".address-2-input").val();
                let city = form.find(".city-input").val();
                let pincode = form.find(".pincode-input").val();
                let isDefault = form.find(".default-checkbox").is(":checked") ? "YES" : "NO";
                let type = form.find(".select-type").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('frontend.addresses.update') }}",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        recipient_first_name: firstName,
                        recipient_last_name: lastName,
                        address_id: addressId,
                        address_line_1: addressLine1,
                        address_line_2: addressLine2,
                        city: city,
                        pincode: pincode,
                        type: type,
                        default: isDefault
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(
                                response.message,
                                '', {
                                    showMethod: "slideDown",
                                    timeOut: 1000,
                                    closeButton: true,
                                });

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $("#" + key + "_error_" + addressId).html(value);
                        });
                    }
                });
            });

            //  Delete Address
            $(".btn-delete-address").click(function() {
                let addressId = $(this).data("id");

                $.ajax({
                    type: "POST",
                    url: "{{ route('frontend.addresses.delete') }}",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        address_id: addressId
                    },
                    success: function(response) {
                        toastr.success(
                            response.message,
                            '', {
                                showMethod: "slideDown",
                                timeOut: 1000,
                                closeButton: true,
                            });

                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        toastr.error(
                            xhr.responseJSON.message,
                            '', {
                                showMethod: "slideDown",
                                timeOut: 1000,
                                closeButton: true,
                            });
                    }
                });
            });
        });

        $(document).ready(function() {

        });
    </script>
@endsection
