<table>
    <thead>

        <tr>
            <th colspan='8'>DISBURSEMENTS BANKS [All In One Report ] : {{$orgName}}</th>
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
            <th>Start Date</th>
            <th>{{$startDate}}</th>
            <th></th>
            <th>End Date</th>
            <th>{{$endDate}}</th>
            <th></th>
        </tr>

        <tr>
            <th colspan="8"></th>
        </tr>
        <tr>
            <th colspan="8">ENTRIES</th>
        </tr>

        <tr>
            <th>Payment Date</th>
            <th>Batch Number</th>
            <th>M-Pesa Receipt</th>
            <th>Full Name</th>
            <th>Destination Number</th>
            <th>Paid Amount</th>
            <th>Transfer Charges</th>
            <th>Withdrawal Fee</th>
            <th>Payment Detail</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

    <?php
	
            
			
		?>

        @foreach($disbursements as $row)
        <tr>
            <td>{{$row->payment_date}}</td>
            <td>{{$row->user_batch_no}}</td>
            <td>{{$row->mpesa_receipt}}</td>
            <td>{{$row->first_name. " " .$row->last_name}}</td>
            <td>{{$row->destination_number  . "(". $row->destination_name . ")"}}</td>
            <td>{{$row->amount}}</td>
            <td>{{$row->tx_charge}}</td>
            <td>{{$row->withdrawal_fee}}</td>
            <td>{{$row->payment_detail}}</td>
            <td>{{$row->status}}</td>
        </tr>
        @endforeach
    </tbody>
</table>