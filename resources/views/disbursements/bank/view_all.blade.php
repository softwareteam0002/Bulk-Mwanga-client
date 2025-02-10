@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 col-dm-12">

                @include('partials.flash_error')

                @if($operation == 'payment')
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Verified amount</th>
                            <td>{{$amountCheck['vAmount']}}</td>
                            <th>Payment amount</th>
                            <td>{{$amountCheck['pAmount']}}</td>

                            <td>
                                @if($amountCheck['status']==true)
                                    <span style="color: red;font-size: 16px;">Amount Miss match</span>
                                @else

                                    <span style="color: #14876a; font-size: 16px;"> Amount is correct</span>

                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">

                {{--                 <p>Uploaded Files</p>--}}
                {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('disbursement-progress')}}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <?php

                use App\Models\Batch;

                ['status' => $status,
                    'status_description' => $failure_reason,
                    'pending' => $pending,
                    'processed' => $processed,
                    'successful' => $successful,
                    'failed' => $failed,
                    'extended' => $extended,
                    'percentage' => $progress,
                    'scheduled_at' => $scheduled_at,
                ] = \App\Helper\BankDisbursementApiHelper::getStatus($batch_no, true, $operation);

//                dd(\App\Helper\BankDisbursementApiHelper::getStatus($batch_no,true,$operation));

                $status_desc = Batch::getStatusName($status);

                ?>

                <h3>{{$operation == 'payment'?'Disbursement':'Verification'}} Summary
                    (Uploaded: {{date('d M Y H:i ',strtotime($batch->created_at))}})</h3>

                <table class="table table-bordered table-sm">
                    <tbody>
                    <tr>
                        <td>Batch Number</td>
                        <td><b>{{$batch->user_batch_no}}</b></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td>
                            <b>{{number_format($disbursements->sum(function($item){ return $item->amount;}),2)}} Tsh</b>
                            @if(!empty($extended['opening_balance']))
                                <div style="display: inline"><em>(Opening
                                        balance: {{number_format($extended['opening_balance'],2)}} Tsh)</em></div>
                            @endif
                        </td>
                    </tr>
                    @if($operation == 'payment')
                        <tr>
                            <td>Charges</td>
                            <td>
                                <b>{{number_format($batch->total_withdrawal_fees + $batch->total_tx_charges,2)}} Tsh</b>
                                <em>(Transactional: {{number_format($batch->total_tx_charges,2)}} Tsh,
                                    Withdrawal: {{number_format($batch->total_withdrawal_fees,2)}} Tsh)</em>
                            </td>
                        </tr>
                        <tr>
                            <td>Include Withdrawal Fee</td>
                            <td><b>{{$batch->with_withdrawal_fee}}</b></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>
                                <em> {{$batch->batch_description}}</em>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>Status</td>
                        <td class="status" data-batch-number="{{$batch_no}}">
                            <b>
                                {{$status_desc}} {{$status == \App\Models\Batch::STATUS_SCHEDULED?"( Sheduled at {$scheduled_at} )":""}} {{$status == \App\Models\Batch::STATUS_FAILED?"( $failure_reason )":""}}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td>Entries</td>
                        <td>
                            <table class="table table-sm" style="margin: 0;">
                                <tbody>
                                <tr>
                                    <th>Total</th>
                                    <th style="color: #3386ba;">Processed</th>
                                    @if($operation == 'payment')
                                        <th style="color: #30ba37">Successful</th>
                                    @endif
                                    <th style="color: #e5b509;">Pending</th>
                                    <th style="color: red">Failed</th>
                                </tr>
                                <tr data-batch-number="{{$batch_no}}">
                                    <td>
                                        <b>{{count($disbursements)}}</b>
                                    </td>
                                    <td style="color: #3386ba;" class="processed">
                                        <b>{{$processed}}</b>{!!" (Tsh ".number_format($extended['processed'],2).")"!!}
                                    </td>
                                    @if($operation == 'payment')
                                        <td style="color: #30ba37" class="successful">
                                            <b>{{$successful}}</b>{!!" (Tsh ".number_format($extended['successful'],2).")"!!}
                                        </td>
                                    @endif
                                    <td style="color: #e5b509;" class="pending">
                                        <b>{{$pending}}</b>{!!" (Tsh ".number_format($extended['pending'],2).")"!!}</td>
                                    <td style="color: red" class="failed">
                                        <b>{{$failed}}</b>{!!" (Tsh ".number_format($extended['failed'],2).")"!!}</td>
                                </tr>
                                </tbody>
                            </table>
                            @if($status==\App\Models\Batch::STATUS_ON_PROGRESS || $status==\App\Models\Batch::STATUS_FAILED || $status==\App\Models\Batch::STATUS_QUEUED)
                                <div class="progress">
                                    <div class="progress-bar {{$status==\App\Models\Batch::STATUS_FAILED?'bg-danger':'bg-info'}} {{$status==\App\Models\Batch::STATUS_ON_PROGRESS?'progress-bar-animated progress-bar-striped':''}}"
                                         role="progressbar"
                                         style="width: {{$progress}}%"
                                         aria-valuenow="50" aria-valuemin="2" aria-valuemax="100"
                                         data-status="{{$status}}" data-batch-number="{{$batch_no}}">
                                        {{!empty($progress)?$progress.'%':''}}
                                    </div>
                                </div>
                            @endif

                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="col-md-12">
                    <form action="{{$operation == 'payment'?url('help/bank-download/batch-disbursement',encrypt($batch_no)):url('help/bank-download/batch-verification',encrypt($batch_no))}}">
                        {{--                <button class="btn btn-info btn-rounded fa fa-download"><span style="margin-left: 5px;">Download This Batch</span></button>--}}
                        <button class="btn btn-danger btn-auto-mobile-margin" style="float: left">Download This Batch
                        </button>

                    </form>
                    @if($operation == 'payment')
                        <a href="{{url('bank-disbursement/payments')}}" class="btn btn-danger btn-auto-mobile-margin"
                           style="float: left; margin-left: 5px;">Back</a>

                    @else
                        <a href="{{url('bank-disbursement/verifications')}}"
                           class="btn btn-danger btn-auto-mobile-margin" style="float: left; margin-left: 5px;">Back</a>

                    @endif
                    @if($batch->batch_status_id==\App\Models\Batch::STATUS_CANCELLED)

                        <a href="#" data-toggle="modal" data-target="#rejection-reason-payment-modal"
                           class="btn btn-danger btn-auto-mobile-margin" style="float: left; margin-left: 5px;">View
                            rejection reason</a>

                    @endif
                </div>

                <table class="table table-striped table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Full Name</th>
                        @if($operation != 'payment')
                            <th>Verified Name</th>
                        @endif
                        <th>Account number</th>
                        <th>Bank Name</th>
                        <th>amount</th>
                        @if($operation == 'payment')
                            <th>Charges</th>
                        @endif
                        <th>Payment Details</th>
                        <th>Status</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($disbursements as $index=>$data)
                        <tr>

                            <td>{{$index+1}}</td>
                            <td>{{$data->first_name.' '.$data->last_name}}</td>
                            @if($operation != 'payment')
                                <th>{{$data->verified_first_name.' '.$data->verified_last_name}}</th>
                            @endif
                            <td>{{$data->account_number}}</td>
                            <td>{{$data->bank}}</td>
                            <td>{{$data->amount}}</td>
                            @if($operation == 'payment')
                                <td>{{number_format($data->withdrawal_fee+$data->tx_charge,2)}}</td>
                            @endif
                            <td>{{$data->payment_detail}}</td>
                            <td>
                                @if($data->payment_status==0)
                                    Pending
                                @elseif($data->payment_status==1)
                                    {{$operation == 'payment'?'Paid':'Verified'}}
                                @elseif($data->payment_status==2)
                                    Failed
                                @elseif($data->payment_status==10)
                                    Sent
                                @endif
                                {{(empty($data->status_description)?'':' - ').$data->status_description}}
                            </td>


                        </tr>

                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>

    </div>
    @if($batch->batch_status_id==\App\Models\Batch::STATUS_CANCELLED)

        @include('disbursements.bank.rejection_reason_modal')

    @endif
@endsection


@section('scripts')
    <script>
        window.onload = function () {
            function refreshStatus(e) {
                let batch = $(e).data('batch-number');
                var formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'Tsh',
                    minimumFractionDigits: 0
                });
                $.ajax({
                    url: '{{ $operation == 'payment'?url("/bank-disbursement/disbursement-status"):url("/bank-disbursement/mnp-search-status")}}/' + batch,
                    type: "GET",
                    async: true,
                    success: function (data) {
                        if (data.response === 'success') {
                            if (data.status === {{\App\Models\Batch::STATUS_ON_PROGRESS}} ||
                                data.status === {{\App\Models\Batch::STATUS_QUEUED}}) {
                                $(e).css('width', data.percentage + '%');
                                $(e).css('font-weight', 'bold');
                                $(e).html(data.percentage + '%');
                                if (data.percentage < 10) {
                                    $(e).css('color', '#333333');
                                } else {
                                    $(e).css('color', '#FFFFFF');
                                }
                                if (data.status === {{\App\Models\Batch::STATUS_ON_PROGRESS}}) {
                                    let tr = $("tr[data-batch-number='" + batch + "']");
                                    $("td.status[data-batch-number='" + batch + "']").find('b').html("On Progress")
                                    tr.find('.processed').html("<b>" + data.processed + "</b> (" + formatter.format(data.extended.processed) + ")")
                                    tr.find('.pending').html("<b>" + data.pending + "</b> (" + formatter.format(data.extended.pending) + ")")
                                    tr.find('.failed').html("<b>" + data.failed + "</b> (" + formatter.format(data.extended.failed) + ")")
                                    if (tr.find('.successful') != undefined && tr.find('.successful') != null) {
                                        tr.find('.successful').html("<b>" + data.successful + "</b> Tsh " + formatter.format(data.extended.successful))
                                    }
                                }
                            } else if (data.status === {{\App\Models\Batch::STATUS_FAILED}} ||
                                data.status === {{\App\Models\Batch::STATUS_COMPLETED}}) {
                                $(e).data('data-status', data.status);
                                $(e).css('width', (data.percentage) + '%');
                                $(e).html((data.percentage) + '%');
                                location.reload();
                            }
                        } else {
                            console.log("Failed")
                        }
                    },
                    contentType: false,
                    processData: false
                });
            }

            setInterval(function () {

                $("[data-batch-number][data-status='{{\App\Models\Batch::STATUS_ON_PROGRESS}}'], [data-batch-number][data-status='{{\App\Models\Batch::STATUS_QUEUED}}']").each(function ($i, e) {
                    refreshStatus(e);
                });
            }, 10000)

        }
    </script>
@endsection
