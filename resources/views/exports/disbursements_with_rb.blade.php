    <table>
    <thead>
    <tr>
        <th colspan='9'>DISBURSEMENTS - Batch No: {{$user_batch_no}}  [{{$orgName}}]</th>
    </tr>
    <tr>
        <th><strong>STATUS</strong></th>
        <th colspan='8'>{{$batch_status}}</th>
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
        <th>On Hold</th>
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
        <th><b>Initiator</b></th><td colspan="2">{{$handlers->operator}}</td>
        <th><b>Approval</b></th><td colspan="2">{{$handlers->handler}}</td>

    </tr>
    <tr>
        <th><b>Initiated date</b></th><td colspan="2">{{$disbursements[0]->initiated_date}}</td>
        <th><b>Approved date</b></th><td colspan="2">{{$disbursements[0]->approved_date}}</td>
        <th><b>Completed date</b></th><td colspan="2">{{$disbursements[0]->batch_completed_date}}</td>

    </tr>
    <tr>
        <th colspan="9">ENTRIES</th>
    </tr>

    <tr>
        <th>Batch Number</th>
        <th>Tx ID</th>
        <th>Details</th>
        <th>Phone Number</th>
        <th>Amount</th>
        <th>Tx Charge</th>
        <th>Withdrawal Fee</th>
        <th>Acc. Balance</th>
        <th>Status</th>

    </tr>
    </thead>
        <tbody>
        @foreach($disbursements as $row)
            @php($i = $row->payment_status==1 && $row->withdrawal_fee?3:($row->payment_status==1?2:1))

            <tr>
                <td>{{$row->user_batch_no}}</td>
                <td>{{$row->mpesa_receipt}}</td>
                <td>
                    {!! $row->first_name.' '.$row->last_name!!}
                </td>
                <td>
                    {!! $row->phone_number.' ('.$row->network_name.')' !!}
                </td>
                <td>{{($row->amount)}}</td>

{{--            @if($row->payment_status==1)--}}

                    <td>{{($row->tx_charge)}}</td>

{{--                @endif--}}

                {{--            @if($row->withdrawal_fee)--}}

                <td>{{($row->withdrawal_fee)}}</td>


                <td>{{( $row->payment_status==1 ? ($balance-=($row->amount+$row->tx_charge+$row->withdrawal_fee)) : $balance)}}</td>
                <td>{{$row->status}}</td>

            </tr>

        @endforeach
    </tbody>
</table>
