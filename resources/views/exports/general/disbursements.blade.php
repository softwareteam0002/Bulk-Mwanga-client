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
        <th>batch_no</th>
        <th>name</th>
        <th>phone_number</th>
        <th>bank</th>
        <th>account_number</th>
        <th>amount</th>
        <th>withdrawal_fee</th>
        <th>transaction_charge</th>
        <th>mpesa_receipt</th>
        <th>payment_status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($disbursements as $row)
    <tr>
        <td>{{$row->user_batch_no}}</td>
        <td>{{$row->first_name.' '.$row->last_name}}</td>
        <td>{{$row->phone_number}}</td>
        <td>{{$row->bank}}</td>
        <td>{{$row->account_number}}</td>
        <td>{{$row->amount}}</td>
        <td>{{$row->withdrawal_fee}}</td>
        <td>{{$row->tx_charge}}</td>
        <td>{{$row->mpesa_receipt}}</td>
        <td>{{$row->status}}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="5">TOTAL</td>
        <td>{{$disbursements->sum('amount')}}</td>
        <td>{{$disbursements->sum('withdrawal_fee')}}</td>
        <td>{{$disbursements->sum('tx_charge')}}</td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
