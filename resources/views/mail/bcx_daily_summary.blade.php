<b>
Transaction Report for <u>{{$start_date}}</u> to <u>{{$end_date}}</u>:<br>
B2C - Wallet Disbursement
<ul>
    <li>Transactions Volume: <u>{{$transaction_volume}}</u></li>
    <li>Transactions Value: <u>{{number_format($transaction_value)}}/=</u></li>
    <li>Charges Value: <u>{{number_format($charges_value)}}/=</u></li>
</ul>
B2B - Bank Disbursement
<ul>
    <li>Transactions Volume: <u>{{$transaction_volume_b2b}}</u></li>
    <li>Transactions Value: <u>{{number_format($transaction_value_b2b)}}/=</u></li>
    <li>Charges Value: <u>{{number_format($charges_value_b2b)}}/=</u></li>
</ul>
</b>