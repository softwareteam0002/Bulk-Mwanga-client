@extends('layouts.master')

@section('content')

    <div class="container custom-report-container">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                <div class="col-md-12">

                    {{ Breadcrumbs::render('reports-per-batch') }}

                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <form method="post" action="{{url('reports/bank/disbursement-by-date')}}">

                    {{csrf_field()}}
                    <div class="row">

                        <div class="col-md-12">

                            @include('partials.flash_error')


                            <div class="row p-t-20">
                                <div class="col-md-4">

                                    <div class="form-group">

                                        @if(!empty($batchPayment))
                                            <input placeholder="Start Date" required type="text" name="startDate"
                                                   id="role-name" class="form-control d-input start-date"
                                                   value="{{$startDate}}">

                                        @else
                                            <input placeholder="Start Date" required type="text" name="startDate"
                                                   id="role-name" class="form-control d-input start-date"
                                                   value="{{old('name')}}">

                                        @endif

                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">

                                        @if(!empty($batchPayment))

                                            <input placeholder="End Date" required type="text" name="endDate"
                                                   id="role-name"
                                                   class="form-control d-input end-date" value="{{$endDate}}">

                                        @else
                                            <input placeholder="End Date" required type="text" name="endDate"
                                                   id="role-name"
                                                   class="form-control d-input end-date" value="{{old('name')}}">

                                        @endif

                                    </div>

                                </div>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_type===1)
                                    <div class="col-md-4">

                                        <div class="form-group">

                                            @if(!empty($batchPayment))

                                                <select name="organization" required id="role-name"
                                                        class="form-control organization-select">


                                                    <option
                                                        value="{{\App\Models\ConstantHelper::ALL_ORGANIZATION_GET_REPORT}}">
                                                        All Organizations
                                                    </option>

                                                    @foreach($organizations as $org)

                                                        <option value="{{$org->id}}" @if($org->id==$organizationId)
                                                            selected
                                                            @endif
                                                        >{{$org->name}}</option>

                                                    @endforeach

                                                </select>

                                            @else

                                                <select name="organization" id="role-name" required
                                                        class="form-control organization-select">

                                                    <option></option>

                                                    <option
                                                        value="{{\App\Models\ConstantHelper::ALL_ORGANIZATION_GET_REPORT}}">
                                                        All Organizations
                                                    </option>

                                                    @foreach($organizations as $org)

                                                        <option value="{{$org->id}}">{{$org->name}}</option>

                                                    @endforeach

                                                </select>
                                            @endif

                                        </div>

                                    </div>

                                @endif


                                <div class="col-md-4">

                                    <div class="form-group">

                                        @if(!empty($batchPayment))
                                            <select type="text" name="type" required class="form-control type">
                                                <option selected disabled>Select Type</option>
                                                {{--                                    <option value="10" @if($type==10) selected @endif>With Multiple Batches</option>--}}
                                                {{--<option value="20" @if($type==20) selected @endif>All In One</option>--}}
                                                <option value="30" @if($type==30) selected @endif>With Multiple Batches
                                                    and
                                                    Running Balance
                                                </option>
                                                <option value="40" @if($type==40) selected @endif>All In One With
                                                    Running
                                                    Balance
                                                </option>
                                            </select>

                                        @else
                                            <select type="text" name="type" required class="form-control type">
                                                <option selected disabled>Select Type</option>
                                                <option value="10">With Multiple Batches</option>
                                                {{--<option value="20">All In One</option>--}}
                                                {{--<option value="30">With Multiple Batches and Running Balance</option>--}}
                                                <option value="40">All In One With Running Balance</option>
                                            </select>
                                        @endif

                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">


                                <div id="multiple">
                                    <button class="btn btn-danger" type="submit">Get</button>

                                </div>

                                <div id="all">
                                    <button class="btn btn-danger" name="excel">Excel</button>
                                    <button class="btn btn-danger" name="csv">Csv</button>
                                    <!-- <button class="btn btn-danger" name="pdf">Pdf</button>-->

                                </div>

                            </div>

                        </div>

                    </div>


                </form>
            </div>


            <div class="result-multiple">

                @if(!empty($batchPayment))

                    <form action="{{url('reports/bank/disbursement-by-date-export/multiple')}}" method="post">

                        {{csrf_field()}}

                        {{--<button class="btn btn-danger" name="pdf">Pdf</button>--}}

                        @if(!$batchPayment->count()>0)
                            <table class="table" class="custom-report-table">
                                <tbody>
                                <tr>
                                    <td colspan="12">No Result Found</td>
                                </tr>
                                </tbody>
                            </table>
                        @else
                            <button class="btn btn-danger" name="excel">Excel</button>
                            <button class="btn btn-danger" name="csv">Csv</button>
                            <!--<button class="btn btn-danger" name="pdf">Pdf</button>-->
                            <button type="button" class="btn btn-danger report-back" onclick="history.back()"><span
                                    style="margin-left: 5px;">Back</span></button>

                            <input type="hidden" name="startDate" value="{{$startDate}}">
                            <input type="hidden" name="endDate" value="{{$endDate}}">

                            <input type="hidden" name="organizationId" value="{{$organizationId}}">
                            <input type="hidden" name="type" value="{{$type??0}}">

                            <table class="table table-bordered table-striped" style="margin-top: 10px;">

                                <thead>
                                <tr>
                                    <th>Batch Number</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Uploaded Date</th>
                                    <th>Organization</th>

                                </tr>

                                </thead>
                                <tbody>


                                @foreach($batchPayment as $payment)

                                    <tr>

                                        <input type="hidden" name="batchNo[]" value="{{$payment->batch_no}}">


                                        <td>{{$payment->user_batch_no}}</td>
                                        <td>{{$payment->total_amount}}</td>
                                        <td class="status">
                                            {{\App\Models\Batch::getStatusName($payment->batch_status_id)}}
                                        </td>

                                        {{-- <td>--}}

                                        {{-- @if($payment->batch_status_id==0)--}}

                                        {{-- Pending--}}

                                        {{-- @elseif($payment->batch_status_id==1)--}}

                                        {{-- Verified--}}

                                        {{-- @elseif($payment->batch_status_id==2)--}}

                                        {{-- Payment Complete--}}

                                        {{-- @endif--}}
                                        {{-- </td>--}}

                                        <td>{{$payment->created_at}}</td>
                                        <td>{{$payment->name}}</td>
                                    </tr>

                                @endforeach
                                </tbody>

                            </table>
                        @endif
                    </form>

                @endif

            </div>

        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $('#all').hide();

        $(".type").on("change", function () {

            var id = $(".type").val();

            if (id == 10 || id == 30) {
                $('#all').hide();
                $('#multiple').show();
            } else if (id == 20 || id == 40) {
                $('#all').show();
                $('#multiple').hide();
                $('.result-multiple').html('');
            }
        });
    </script>

@stop
