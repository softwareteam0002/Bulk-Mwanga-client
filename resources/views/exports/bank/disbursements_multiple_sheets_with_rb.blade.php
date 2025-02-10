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
        <th>Approval</th><td colspan="2">{{$handlers->handler}}</td>
        
    </tr>

    <tr>
        <th><b>Initiated date</b></th><td colspan="2">{{$disbursements[0]->initiated_date??''}}</td>
        <th><b>Approved date</b></th><td colspan="2">{{$disbursements[0]->approved_date??''}}</td>
        <th><b>Completed date</b></th><td colspan="2">{{$disbursements[0]->batch_completed_date??''}}</td>

    </tr>
    <tr>
        <th colspan="8"></th>
    </tr>
    <tr>
        <th colspan="8">ENTRIES</th>
    </tr>
<!-------Batch Number		Transaction Id	Details				Amount		Acc. Balance
---->
    <tr>
        <th>Batch Number</th>
        <th>Tx ID</th>
        <th>Details</th>
        <th>Account number</th>
        <th>Bank</th>
        <th>Amount</th>
        <th>Acc. Balance</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($disbursements as $row)
    @php($i = $row->payment_status==1 && $row->withdrawal_fee?3:($row->payment_status==1?2:1))

    <tr>
        <td rowspan="{{$i}}">{{$row->user_batch_no}}</td>
        <td rowspan="{{$i}}">{{$row->mpesa_receipt}}</td>


        <td>{!! $row->first_name.' '.$row->last_name!!}</td>
        <td>{!! $row->account_number.'' !!}</td>
        <td>{!!  $row->bank !!}</td>
        <td>{{($row->amount)}}</td>
        <td>{{( $row->payment_status==1 ? ($balance-=$row->amount) : $balance)}}</td>
        <td>{{$row->status}}</td>
    </tr>
        @if($row->payment_status==1)
            <tr>
                <td>Tx Charge</td>
                <td>{{($row->tx_charge)}}</td>
                <td>{{($balance-=$row->tx_charge)}}</td>
                <td>{{$row->status}}</td>
            </tr>
        @endif
        @if($row->withdrawal_fee)
            <tr>
                <td>Withdraw Fee</td>
                <td>{{($row->withdrawal_fee)}}</td>
                <td>{{($balance-=$row->withdrawal_fee)}}</td>
                <td></td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
