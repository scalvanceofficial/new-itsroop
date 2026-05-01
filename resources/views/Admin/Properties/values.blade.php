<div class="row valueRow">
    <div class="col-md-12">
        <div
            style="border: 2px solid #5d87ff; padding: 13px 25px; margin-top: 13px; margin-bottom: 13px; border-radius: 10px;">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="names[{{ $random_number }}]" class="form-control" placeholder="Value"
                        required />
                </div>

                @if ($is_color == 'YES')
                    <div class="col-md-3 color-input-field" id="color-input-field-{{ $random_number }}">
                        <div class="form-group">
                            <input type="color" class="form-control form-control-color"
                                name="colors[{{ $random_number }}]" title="Choose your color" />
                        </div>
                    </div>
                @else
                    <div class="col-md-3"></div>
                @endif


                <div class="col-md-2">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="status_{{ $random_number }}"
                            name="statuses[{{ $random_number }}]" value="ACTIVE" checked>
                    </div>
                </div>


                <div class="col-md-2 d-flex justify-content-end">
                    <input type="number" id="value_index_{{ $random_number }}" name="indexes[{{ $random_number }}]"
                        class="form-control index" placeholder="Index" required />
                </div>

                <div class="col-md-1 d-flex justify-content-end" style="padding:5px;">
                    <button type="button" class="btn btn-danger btn-sm float-right removeValueBtn">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
