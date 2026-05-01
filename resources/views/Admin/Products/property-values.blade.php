<div class="row mt-3">
    @foreach ($properties as $property)
        <div class="col-12">
            <p class="card-subtitle mb-3">{{ $property->name }}</p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-blue">
                        <tr>
                            @if ($property->is_color == 'YES')
                                <th>Color</th>
                                <th>Color Name</th>
                                <th>Color Code</th>
                            @endif
                            <th>Name</th>
                            <th>Status</th>
                            <th>Primary</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $property_values = $property->propertyValues()->orderBy('index')->get(); @endphp --}}
                        @php
                            $property_values = $property->propertyValues->where('status', 'ACTIVE')->sortBy('index');
                        @endphp

                        @foreach ($property_values as $property_value)
                            @php
                                $checked = false;
                                $checked_primary = false;
                                $color_name = '';
                                $color_code = '';
                            @endphp
                            @if ($product)
                                @foreach ($product->productPropertyValues as $product_property_value)
                                    @if ($property_value->id == $product_property_value->property_value_id)
                                        @php
                                            $checked = true;
                                            $checked_primary = $product_property_value->is_primary == 'YES';
                                            $color_name = $product_property_value->color_name;
                                            $color_code = $product_property_value->color_code;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                @if ($property->is_color == 'YES')
                                    <td class="text-center align-middle" width="2%">
                                        <div
                                            style="width: 15px; height: 15px; border-radius: 50%; background-color: {{ $property_value->color }}; display: inline-block;">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text"
                                            name="color_names[{{ $property->id }}][{{ $property_value->id }}]"
                                            class="form-control" value="{{ $color_name }}"
                                            placeholder="Enter color name">
                                    </td>
                                    <td>
                                        {{-- <input type="color"
                                            name="color_codes[{{ $property->id }}][{{ $property_value->id }}]"
                                            class="form-control form-control-color" value="{{ $color_code }}"> --}}
                                        <input type="color"
                                            name="color_codes[{{ $property->id }}][{{ $property_value->id }}]"
                                            class="form-control form-control-color"
                                            value="{{ $color_code ?: $property_value->color }}">

                                    </td>
                                @endif
                                <td>{{ $property_value->name }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="property_values[{{ $property->id }}][]"
                                            class="form-check-input color-status-switch"
                                            value="{{ $property_value->id }}" {{ $checked ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="radio" name="is_primary[{{ $property->id }}]"
                                            class="form-check-input color-status-switch"
                                            value="{{ $property_value->id }}" {{ $checked_primary ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    <div id="property_values-error" style="color:red"></div>
</div>
