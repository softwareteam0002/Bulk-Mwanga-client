<table class="custom-full-width">
    <tr>
		<th>Transaction ID</th>
		<th>Date & Time</th>
		<th>Receipt #</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Organization</th>
		<th>Short Code</th>
		<th class='text-right'>Amount</th>
		<th class='text-right'>Charges</th>
		<th class='text-right'>Account Number</th>
    </tr>

    <?php 
        $total_amount = 0;
        $total_charges = 0;
    ?>
    @foreach($transactions as $tx)
    <?php 
        $total_amount  =  $tx->amount + $total_amount; 
        $total_charges  =  $tx->tx_charge + $total_charges; 
    ?>
    <tr>
		<td>{{$tx->tx_id}}</td>
		<td>{{$tx->created_at }}</td>
		<td>{{$tx->mpesa_receipt }}</td>
		<td>{{$tx->first_name }}</td>
		<td>{{$tx->last_name }}</td>
		<td>{{$tx->organization_name }}</td>
		<td>{{$tx->short_code }}</td>
		<td class='text-right'>{{$tx->amount}}</td>
		<td class='text-right'>{{$tx->tx_charge}}</td>
		<td class='text-right'>{{$tx->account_name}}</td>
    </tr>
    @endforeach
    <tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><b>Total</b></td>
		<td class='text-right'><b><u>{{ $total_amount }}</u></b></td>
		<td class='text-right'><b><u>{{ $total_charges }}</u></b></td>
		<td></td>
    </tr>
</table>
