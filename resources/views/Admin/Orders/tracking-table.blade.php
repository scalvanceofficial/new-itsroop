<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="table-primary">
            <tr>
                <th>Date & Time</th>
                <th>Activity</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($tracking_activities))
                @foreach ($tracking_activities as $tracking_activity)
                    <tr>
                        <td>{{ toIndianDateTime($tracking_activity['date']) }}</td>
                        <td>{{ $tracking_activity['activity'] }}</td>
                        <td>{{ $tracking_activity['location'] }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
