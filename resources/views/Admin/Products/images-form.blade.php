<form action="{{ route('admin.products.images.store', ['product' => $product->route_key]) }}" method="POST"
    enctype="multipart/form-data" id="productImagesForm">
    @csrf

    <div class="row mt-3">
        <input type="hidden" name="property_id" value="{{ $property->id }}">
        @foreach ($product_property_values as $product_property_value)
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>{{ $product_property_value->propertyValue->name }}</h5>
                    </div>
                    <div class="card-body" id="properties">
                        <div class="row">
                            <div class="col-12">
                                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd;">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-blue">
                                            <tr>
                                                <th>Upload Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="file" class="form-control"
                                                        name="images[{{ $product_property_value->property_value_id }}][]"
                                                        multiple accept="image/*">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <span class="spinner-span" role="status" aria-hidden="true"></span>
                        <span class="save-btn-text">Save</span>
                        &nbsp;
                        <i class="ti ti-device-floppy"></i>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('admin.products.index') }}" type="button" class="btn btn-secondary">
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
    $('#productImagesForm').submit(function(e) {
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
                        window.location.href =
                            "{!! route('admin.products.index') !!}";
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
                toastr.error(
                    'There are some errors in Form. Please check your inputs',
                    '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
            }
        });
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
</script>
