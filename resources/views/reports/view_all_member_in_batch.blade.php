@extends('layouts.master')

@section('content')

    <div class="container custom-report-container">

        <div class="row">

            <div class="col-md-12">

                @include('partials.flash_error')

            </div>
            <div class="col-md-12 margin-bottom-custom">

                {{--                 <p>Uploaded Files</p>--}}
                {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('reports-per-inbatch')}}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <table class="table table-bordered  table-sm">
                    <tbody>
                    <tr>
                        {{--                      todo list return user batch alone.--}}
                        <td>Batch Number</td>
                        <td class="red-color">{{$user_batch_no}}</td>
                    </tr>
                    <tr>
                        <td>Entries</td>
                        <td><b>{{$entries}}</b></td>
                    </tr>

                    <tr>
                        <td>Total Amount</td>
                        <td><b>{{number_format($amount)}} Tsh</b></td>
                    </tr>
                    </tbody>
                </table>
                <form action="{{url('reports/payment/batch-payment-per-batch',encrypt($batch_no))}}" method="post">

                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-control-label font-weight-bold">
                                <input name="running_balance" value="1" type="checkbox" checked readonly
                                       onclick="return false"> &nbsp;
                                Download With running balance
                            </label>
                            <br>
                            <!--<button class="btn btn-danger fa fa-download" name="pdf"><span style="margin-left: 5px;">Pdf</span></button>-->
                            <button class="btn btn-danger fa fa-download" name="excel"><span style="margin-left: 5px;">Excel</span>
                            </button>
                            <button class="btn btn-danger fa fa-download" name="csv"><span
                                        style="margin-left: 5px;">Csv</span></button>
                            {{--                         <button  type="button" class="btn btn-danger report-back"  onclick="history.back()" ><span style="margin-left: 5px;">Back</span></button>--}}

                        </div>
                    </div>
                </form>
                <table class="table table-striped table-bordered" id="table">

                    <thead>

                    <tr>

                        <th>No</th>

                        <th>Full Name</th>
                        <th>Status</th>
                        <th>Uploaded Date</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($disbursementsPayments as $index=>$data)
                        <tr>

                            <td>{{$index+1}}</td>

                            <td>{{$data->first_name.' '.$data->last_name}}</td>

                            <td class="status">
                                {{\App\Models\DisbursementPayment::paymentStatus($data->payment_status)}}
                            </td>
                            <td>{{$data->created_at}}</td>


                        </tr>

                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>

    </div>

@endsection
