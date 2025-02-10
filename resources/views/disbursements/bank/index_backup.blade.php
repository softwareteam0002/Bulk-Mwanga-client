@extends('layouts.master')

@section('content')

    <div class="container verif-page">

        <div class="row">

            <div class="col-md-12 col-sm-12">

                @include('partials.flash_error')

            </div>

            <div class="col-md-12 verif-breadcrumb">

                {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('batches-for-verification')}}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <table class="table table-striped table-bordered" id="table">

                    <thead>

                    <tr>

                        <th>No</th>
                        <th>Batch Number</th>
                        <th>Total Amount</th>
                        <th>Uploaded Date</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Actions</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($verification as $index=>$data)
                        <tr data-batch-number="{{$data->batch_no}}">

                            @if(($index+1)==1&&$data->batch_status_id==\App\Models\Batch::STATUS_PENDING)
                                <td class="stat-row">{{$index+1}}</td>
                            @else
                                <td>{{$index+1}}</td>
                            @endif
                            <td>{{$data->user_batch_no}}</td>
                            <td>{{$data->total_amount}}</td>
                            <td>{{$data->created_at}}</td>
                            <td class="status">
                                {{\App\Models\Batch::getStatusName($data->batch_status_id)}}
                            </td>
                            <td>
                                    <?php
                                    ['status' => $status,
                                        'pending' => $pending,
                                        'processed' => $processed,
                                        'failed' => $failed] = \App\Helper\BankDisbursementApiHelper::getVerificationStatus($data->batch_no);
                                    $progress = \App\Helper\BankDisbursementApiHelper::getVerificationStatus($data->batch_no, true);
                                    ?>
                                @if($status==\App\Models\Batch::STATUS_ON_PROGRESS || $status==\App\Models\Batch::STATUS_QUEUED || $status==\App\Models\Batch::STATUS_FAILED)
                                    <div class="progress">
                                        <div class="progress-bar {{$status==\App\Models\Batch::STATUS_FAILED?'bg-danger':'bg-info'}} {{$status==\App\Models\Batch::STATUS_ON_PROGRESS?'progress-bar-animated progress-bar-striped':''}}"
                                             role="progressbar"
                                             style="width: {{$progress}}%"
                                             aria-valuenow="50" aria-valuemin="2" aria-valuemax="100"
                                             data-status="{{$status}}" data-batch-number="{{$data->batch_no}}">
                                            {{!empty($progress)?$progress.'%':''}}
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>

                                @if($data->batch_status_id==\App\Models\Batch::STATUS_PENDING)
                                    <a href="#"
                                       onclick="verifyBatch('{{url('bank-disbursement/bankname-search-request',$data->batch_no)}}')"
                                       class=" btn btn-sm btn-danger tooltip-voda fa fa-check ">
                                        <span class="tooltip-text">Verify Batch</span>

                                    </a>
                                @endif
                                <a href="{{url('bank-disbursement/view',encrypt($data->batch_no))}}"
                                   class="tooltip-voda">
                                    <i class=" btn btn-sm btn-danger fa fa-eye"></i>
                                    <span class="tooltip-text">View Batch</span>

                                </a>
                                <a href="{{url('help/bank-download/batch-verification',encrypt($data->batch_no))}}"
                                   class="tooltip-voda">
                                    <i class="btn btn-sm btn-danger fa fa-download"></i>
                                    <span class="tooltip-text">Download Batch</span>

                                </a>
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>
                {{$verification->links()}}

            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        window.onload = function () {
            function refreshStatus(e) {
                let batch = $(e).data('batch-number')
                $.ajax({
                    url: '{{url("/bank-disbursement/mnp-search-status")}}/' + batch,
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
                                    tr.find('.status').html("On Progress")
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

        function verifyBatch(url) {
            var title = "Warning";
            var body = "Are you sure you want to verify this batch?";
            $('#mi-modal .modal-title').html(title);
            $('#mi-modal .modal-body').html(body);
            $('#mi-modal').modal('show');

            $('#modal-btn-yes').on('click', function () {
                location.href = url;
                $('#mi-modal').modal('hide');
            });
            $('#modal-btn-no').on('click', function () {
                $('#mi-modal').modal('hide');
            });
        }
    </script>
@endsection
