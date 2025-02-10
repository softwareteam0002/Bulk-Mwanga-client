<table>
    <thead>


    <tr>

        <th colspan='8'>DISBURSEMENTS BANK - Batch No # {{$batch_no}}  [{{$orgName}}]</th>
    </tr>
    <tr>
        <th colspan='8'>SUMMARY</th>
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
        <th>Initiator</th><td colspan="2">{{$handlers->operator}}</td>
        <th>Approver</th><td colspan="2">{{$handlers->handler}}</td>

    </tr>
            
    <tr>
        <th><b>Initiated date</b></th><td colspan="2">{{$disbursements[0]->initiated_date}}</td>
        <th><b>Approved date</b></th><td colspan="2">{{$disbursements[0]->approved_date}}</td>
        <th><b>Completed date</b></th><td colspan="2">{{$disbursements[0]->batch_completed_date}}</td>

    </tr>
    <tr>
        <th colspan="8"></th>
    </tr>
    <tr>
        <th colspan="8">ENTRIES</th>
    </tr>

    <tr>
        <th>Payment Date</th>
        <th>M-Pesa Receipt</th>
        <th>Full Name</th>
        <th>Account Number</th>
        <th>Paid Amount</th>
        <th>Transfer Charges</th>
        <th>Withdrawal Fee</th>
        <th>Payment Detail</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($disbursements as $row)
    <tr>
        <td>{{$row->payment_date}}</td>
        <td>{{$row->mpesa_receipt}}</td>
        <td>{{$row->first_name . " ". $row->last_name }}</td>
        <td>{{ $row->account_number . "(". $row->bank . ")" }}</td>
        <td>{{$row->amount}}</td>
        <td>{{$row->tx_charge}}</td>
        <td>{{$row->withdrawal_fee}}</td>
        <td>{{$row->payment_detail}}</td>
        <td>{{$row->status}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
