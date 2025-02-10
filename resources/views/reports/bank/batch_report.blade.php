@if(\Illuminate\Support\Facades\Auth::user()->user_type==\App\Models\ConstantHelper::INTERNAL_USER)
    <table class="table table-bordered  table-striped  table-condensed">

        <thead>

        <tr>
            <th>Batch Number</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Uploaded Date</th>
            <th>Actions</th>

        </tr>

        </thead>
        <tbody>

        <tr>

            <td>{{$batchPayment->user_batch_no}}</td>
            <td>{{$batchPayment->total_amount}}</td>

            <td class="status">
                {{\App\Models\Batch::getStatusName($batchPayment->batch_status_id)}}
            </td>
            <td>{{$batchPayment->created_at}}</td>

            <td>

                <a href="{{url('reports/payment/view-all-batch',encrypt($batchPayment->batch_no))}}"
                   class=" btn btn-danger fa fa-eye"></a>
                {{--                <button class=" btn btn-danger fa fa-download"></button>--}}

                <form action="{{url('#')}}" method="post">


                </form>

            </td>

        </tr>


        </tbody>

    </table>

@endif
