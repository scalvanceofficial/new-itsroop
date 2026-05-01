@extends('layouts.admin')
@section('title')
    Videos
@endsection
@section('content')
    <style>
        table {
            table-layout: fixed;
            width: 100%;
        }

        th,
        td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Videos</h5>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.videos.create') }}" class="btn btn-info">
                                    Create
                                    &nbsp;
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered  text-nowrap mb-0 align-middle" id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Index</h6>
                                        </th>
                                        <th width="35%">
                                            <h6 class="fs-3 fw-semibold mb-0">Url</h6>
                                        </th>
                                        <th width="25%">
                                            <h6 class="fs-3 fw-semibold mb-0">Video</h6>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
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
                        <input type="hidden" name="video_id" id="videoId">
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
                                <button type="submit" class="btn btn-primary ">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                ajax: {
                    url: '{!! route('admin.videos.data') !!}',
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
                        name: 'videos.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'videos.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'index',
                        name: 'videos.index',
                        orderable: false
                    },
                    {
                        data: 'url',
                        name: 'url',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'video',
                        name: 'videos.video',
                        orderable: false,
                        searchable: false
                    },

                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2, 3],
                    className: "text-center"
                }, ],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            $('#updateIndexForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.videos.index.update') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#videoId').val('');
                        $("#indexDropdown").val('');
                        $("#index-error").html('');
                        $('#datatable').DataTable().draw(false);
                        $('#updateIndexModal').modal('hide');

                        toastr.success('Index updated successfully.');
                    },
                    error: function(xhr) {
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

        $(document).on('click', '.videoIndexBtn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var index = $(this).data('index');

            $('#updateIndexModal').modal('show');
            $('#videoId').val(id);
            $("#indexDropdown").val(index).val(index);
        });

        $(document).on('change', '.video-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.videos.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    route_key: routeKey,
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
