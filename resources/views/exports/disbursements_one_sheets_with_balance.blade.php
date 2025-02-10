
<table>
    <thead>

    <tr>
        <th colspan='9'>DISBURSEMENTS [General Report]</th>
    </tr>

    <tr>
        <th colspan='9'>SUMMARY</th>
    </tr>

    <tr>
        <th></th>
        <th>Total</th>
        <th>Processed</th>
        <th>Successful</th>
        <th>Failed</th>
        <th>Unknown</th>
        <th></th>
    </tr>

    <tr>
        <th>Entries</th>
        <th>{{$entries['total']}}</th>
        <th>{{$entries['processed']}}</th>
        <th>{{$entries['successful']}}</th>
        <th>{{$entries['failed']}}</th>
        <th>{{$entries['unknown']}}</th>
        <th></th>
    </tr>

    <tr>
        <th>Amount</th>
        <th>{{$amounts['total']}}</th>
        <th>{{$amounts['processed']}}</th>
        <th>{{$amounts['successful']}}</th>
        <th>{{$amounts['failed']}}</th>
        <th>{{$amounts['unknown']}}</th>
        <th></th>
    </tr>
    <tr>
        {{--        <th>Initiator</th><td colspan="2">{{$handlers->operator}}</td>--}}
        {{--        <th>Approval</th><td colspan="2">{{$handlers->handler}}</td>--}}

    </tr>

    <tr>
        <th colspan="9"></th>
    </tr>
    <tr>
        <th colspan="9">ENTRIES</th>
    </tr>

    <tr>
        <th>Batch Number</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Phone Number</th>
        <th>Network Name</th>
        <th>Amount</th>
        <th>Withdrawal Fee</th>
        <th>Payment status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($disbursements as $row)
        <tr>
            <td>{{$row->user_batch_no}}</td>
            <td>{{$row->first_name}}</td>
            <td>{{$row->last_name}}</td>
            <td>{{$row->phone_number}}</td>
            <td>{{$row->network_name}}</td>
            <td>{{$row->amount}}</td>
            <td>{{$row->withdrawal_fee}}</td>
            <td>{{$row->status}}</td>
            <td>{{$row->current_balance}}</td>

        </tr>
    @endforeach
    </tbody>
</table>
