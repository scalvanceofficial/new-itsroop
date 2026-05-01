@extends('layouts.admin')
@section('title')
    Currencies
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-9 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Currencies</h5>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <a href="{{ route('admin.currencies.create') }}" class="btn btn-info">
                                    Create &nbsp; <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered text-nowrap mb-0 align-middle" id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Symbol</th>
                                        <th>Exchange Rate</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('admin.currencies.data') !!}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    { data: 'symbol', name: 'symbol' },
                    { data: 'exchange_rate', name: 'exchange_rate' },
                    { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $(document).on('change', '.change-status', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('admin.currencies.change-status') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    }
                });
            });
        });
    </script>
@endsection
