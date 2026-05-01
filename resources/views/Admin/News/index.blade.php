@extends('layouts.admin')
@section('title')
    News
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">News</h5>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.news.create') }}" class="btn btn-info">
                                    Create
                                    &nbsp;
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm w-100 text-nowrap mb-0 align-middle"
                                id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th width="5">
                                            <h6 class="fs-3 fw-semibold mb-0">Index</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Title</h6>
                                        </th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="updateIndexModal" tabindex="-1" role="dialog" aria-labelledby="updateIndexModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateIndexModalLabel">Update Index</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateIndexForm">
                        @csrf
                        <input type="hidden" name="news_id" id="newsId">
                        <div class="form-group mb-2">
                            <label for="index">Index</label>
                            <select class="form-control" id="indexDropdown" name="index">
                                @for ($i = 1; $i <= $max_index; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <span class="text-danger" id="index-error"></span>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <span class="spinner-span" role="status" aria-hidden="true"></span>
                                    <span class="save-btn-text">Save</span>
                                    &nbsp;
                                    <i class="ti ti-device-floppy"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            var dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                ajax: {
                    url: '{!! route('admin.news.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'news.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'news.id',
                        searchable: false
                    },
                    {
                        data: 'index',
                        name: 'news.index',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'title',
                        name: 'news.title'
                    },


                ],
                order: [],
                columnDefs: [{
                    targets: [],
                    className: "text-center"
                }, ],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

            $('#updateIndexForm').on('submit', function(e) {
                e.preventDefault();
                $('#submit-btn').attr('disabled', true)
                $('.spinner-span').addClass('spinner-border spinner-border-sm')

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.news.index.update') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm');

                        $('#newsId').val('');
                        $("#indexDropdown").val('');
                        $("#index-error").html('');
                        $('#datatable').DataTable().draw(false);
                        $('#updateIndexModal').modal('hide');

                        toastr.success('Index updated successfully.');
                    },
                    error: function(xhr) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm')
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });

                        if (xhr.responseJSON.status == "failed") {
                            toastr.error(xhr.responseJSON.message);
                        }
                    }
                });
            });

        });

        $(document).on('click', '.newsIndexBtn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var index = $(this).data('index');

            $('#updateIndexModal').modal('show');
            $('#newsId').val(id);
            $("#indexDropdown").val(index).val(index);
        });

        $(document).on('change', '.news-status-switch', function(e) {
            e.preventDefault();
            var news_id = $(this).data('news_id');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.news.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    news_id: news_id,
                    status: status
                },
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        if ($.fn.DataTable.isDataTable("#datatable")) {
                            $('#datatable').DataTable().draw(false);
                        }
                    } else {
                        toastr.error(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },

                error: function(data) {
                    toastr.error('Something went wrong!');
                }
            });
        });
    </script>
@endsection
