@extends('layouts.admin')
@section('title')
  Visitor Logs
@endsection
@section('content')
  <style>
    /* Only allow wrapping for 'User Agent' */
    #datatable td,
    #datatable th {
      white-space: nowrap;
    }

    #datatable td.wrap-text,
    #datatable th.wrap-text {
      white-space: normal !important;
      word-break: break-word;
    }
  </style>

  <section>
    <div class="card">
      <div class="card-header border-bottom d-flex justify-content-between">
        <h5 class="card-title fw-semibold">Visitor Logs</h5>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-sm w-100" id="datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>IP</th>
                <th>URL</th>
                <th>Referrer</th>
                <th>Visit Count</th>
                <th>Last Visited At</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </section>

  <script>
    $(function() {
      $('#datatable').DataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: false,
        scrollX: false, // wrapping instead of scrolling
        ajax: {
          url: '{{ route('admin.visitorlog.data') }}',
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
            data: 'ip',
            name: 'ip'
          },
          {
            data: 'url',
            name: 'url'
          },
          {
            data: 'referrer',
            name: 'referrer'
          },
          {
            data: 'visit_count',
            name: 'visit_count'
          },
          {
            data: 'last_visited_at',
            name: 'last_visited_at'
          },
        ],
        order: [],
        columnDefs: [{
          targets: [0, 4],
          className: "text-center"
        }]
      });

      $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
        .addClass("btn btn-primary me-1");
    });
  </script>
@endsection
