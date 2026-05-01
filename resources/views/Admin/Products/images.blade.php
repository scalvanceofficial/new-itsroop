@extends('layouts.admin')
@section('title')
    Product Images
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5>Product Images</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Properties</label>
                            <select class="form-control form-select" name="property_id" id="property_id">
                                <option value="">Select</option>
                                @foreach ($properties as $property)
                                    <option value="{{ $property->id }}"
                                        {{ $product->primary_property_id == $property->id ? 'selected' : '' }}>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="property_id_error" style="color:red"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($product->productImages->isNotEmpty())
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd;">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-blue">
                                            <tr>
                                                <th width="10%">#</th>
                                                <th>Image</th>
                                                <th width="10%">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->productImages as $product_image)
                                                <tr>
                                                    <td>
                                                        <b>{{ $product_image->propertyValue->name }}</b>
                                                    </td>
                                                    <td>
                                                        <img src="{{ Storage::url($product_image->image) }}" width="150px"
                                                            height="150px">
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-danger productImageDeleteBtn"
                                                            data-id={{ $product_image->id }}>
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div id="imageForm">

    </div>

    <script>
        $(document).ready(function() {
            getImagesForm();
            $('#property_id').change(function() {
                getImagesForm();
            });

            $('.productImageDeleteBtn').click(function(e) {
                let button = $(this);
                let productImageId = $(this).attr('data-id');

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.products.images.destroy', ['product' => $product->route_key]) }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_image_id: productImageId,

                    },
                    success: function(data) {
                        button.closest('tr').remove();
                    }
                });
            })
        });

        function getImagesForm() {
            let propertyId = $('#property_id').val()
            $.ajax({
                url: "{{ route('admin.products.images.form', ['product' => $product->route_key]) }}",
                type: "POST",
                data: {
                    property_id: propertyId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#imageForm').html(response);
                }
            });
        }
    </script>
@endsection
