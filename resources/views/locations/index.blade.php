<x-toolbar resource="locations" label="Location" />

<div class="table-container">
    <table class="custom-table">
        <thead>
        <tr>
            <th>ID Location</th>
            <th>Zone Group</th>
            <th>Location Category</th>
            <th>Location Attribute</th>
            <th>Create Time</th>
            <th>Edit Time</th>
        </tr>
        </thead>

        <tbody>
            @foreach($locations as $location)
                <tr onclick="openDetail('locations', '{{ $location->id }}')" 
                    style="cursor:pointer;" 
                    class="row-hover">
                    
                    <td class="font-bold">{{ $location->id }}</td>
                    <td>{{ $location->zone_group ?? '-' }}</td>
                    <td>{{ $location->location_category ?? '-' }}</td>
                    <td>{{ $location->location_attribute }}</td>
                    <td>{{ $location->created_at }}</td>
                    <td>{{ $location->updated_at }}</td>

                </tr>
            @endforeach

            @if($locations->isEmpty())
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px;">
                        Data location belum tersedia. Silakan klik "Add Location".
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>