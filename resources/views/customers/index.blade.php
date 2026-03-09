<x-toolbar resource="customers" label="Customer" />

<div class="table-container">
    <table class="custom-table">
        <thead>
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Company Name</th>
            <th>Phone</th>
            <th>Email Address</th>
            <th>Full Address</th>
            <th>Create Time</th>
            <th>Edit Time</th>
        </tr>
        </thead>

        <tbody>
        @foreach($customers as $customer)
            <tr onclick="openDetail('customers','{{ $customer->id }}')" 
                style="cursor:pointer;">

                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->company_name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->email }}</td>

                <td class="truncate" title="{{ $customer->address }}">
                    {{ $customer->address }}
                </td>

                <td>{{ $customer->created_at }}</td>
                <td>{{ $customer->updated_at }}</td>

            </tr>
        @endforeach

        @if($customers->isEmpty())
            <tr>
                <td colspan="9" style="text-align:center; padding:20px;">
                    Data customer tidak ditemukan
                </td>
            </tr>
        @endif

        </tbody>
    </table>
</div>