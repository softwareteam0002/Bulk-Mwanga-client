@extends('layouts.master')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-approve" action="{{url('disbursement/payment-request')}}" method="get">
                    <div class="modal-body">
                        Are you sure you want to approve this batch?
                    </div>


                    <input type="hidden" name="organizationId" value="" id="organization-deactivate">


                    <input type="hidden" name="batch_no" value="" id="batch-no-payment">


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="yesApprove" class="btn btn-danger">Approve</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('help/batch-reject')}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        Are you sure you want to reject this batch?
                    </div>
                    <div class="form-group p-2 m-2">

                        <label>Reason</label>
                        <textarea class="form-control" name="reason"></textarea>

                    </div>

                    <input type="hidden" name="batchNo" id="batchNoPayment">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reject</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container verif-page">

        <div class="row">

            <div class="col-md-12 col-sm-12">

                @include('partials.flash_error')

            </div>
            <div class="col-md-12 verif-breadcrumb">
                {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('disbursement-progress')}}
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>Batch Number</th>
                        <th>Total Amount</th>
                        <th>Uploaded Date</th>
                        <th>Initiator</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Handler</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($orgaDisbursements as $index=>$data)
                        <tr data-batch-number="{{$data->batch_no}}">

                            <td>{{$index+1}}</td>
                            <td>{{$data->user_batch_no}}</td>
                            <td>{{$data->total_amount}}</td>
                            <td>{{$data->created_at}}</td>
                            <td>{{$data->operator}}</td>
                            <td class="status">
                                {{\App\Models\Batch::getStatusName($data->batch_status_id)}}
                            </td>

                            <td>

                            </td>

                            <td>{{$data->handler}}</td>

                            <td>

                                @if($data->batch_status_id==\App\Models\Batch::STATUS_PENDING)

                                    {{--                                        <form id="form-approve" action="{{url('disbursement/payment-request',encrypt($data->batch_no))}}" method="get">--}}

                                    <!-- <button  type="button" onclick="loadApprovalModal('{{encrypt($data->batch_no)}}')"  id="{{encrypt($data->batch_no)}}" class="btn btn-danger btn-sm  fa fa-check tooltip-voda approve-payment-modal">
                                                <span class="tooltip-text">Approve Batch</span>
                                            </button>-->
                                    <button type="button" data-batch-no="{{encrypt($data->batch_no)}}"
                                            class="btn btn-danger btn-sm  fa fa-check tooltip-voda approve-payment-modal"
                                            data-toggle="modal" data-target="#approveModal">

                                    </button>

                                    {{--                                        </form>--}}

                                    <!-- <button  type="button" onclick="loadRejectionModal('{{encrypt($data->batch_no)}}')"  id="{{encrypt($data->batch_no)}}" class="btn btn-danger btn-sm  fa fa-remove tooltip-voda reject-payment">
                                                <span class="tooltip-text">Reject Batch</span>

                                            </button>-->

                                    <button type="button" data-batch-no="{{encrypt($data->batch_no)}}"
                                            class="btn btn-danger btn-sm  fa fa-remove tooltip-voda reject-payment"
                                            data-toggle="modal" data-target="#rejectModal">

                                    </button>

                                @endif
                                {{--                                        @if($data->batch_status_id == \App\Batch::STATUS_ON_HOLD || $data->batch_status_id==\App\Batch::STATUS_FAILED)--}}
                                {{--                                            <a href="{{url('disbursement/payment-retry',$data->batch_no)}}" class="btn btn-danger btn-sm  fa fa-repeat tooltip-voda btn-retry-payment">--}}
                                {{--                                                <span class="tooltip-text">Retry payment</span>--}}
                                {{--                                            </a>--}}
                                {{--                                        @endif--}}
                                <a href="{{url('disbursement-payment/view',encrypt($data->batch_no))}}">

                                    <i class=" btn btn-danger btn-sm  fa fa-eye tooltip-voda">

                                        <span class="tooltip-text">View Batch</span>

                                    </i>

                                </a>

                                <form action="{{route('download.batch-payment',[encrypt($data->batch_no)])}}">

                                    <button type="submit" class=" btn btn-danger btn-sm  fa fa-download tooltip-voda">
                                        <span class="tooltip-text">Download Batch</span>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>
                {{$orgaDisbursements->links()}}
            </div>
        </div>

    </div>

    @include('disbursements.reject_modal')
    @include('disbursements.approve_modal')

@endsection

@section('scripts')
    <script>
        function loadApprovalModal(id) {
            let batchId = id;
            $('#batch-no-payment').val(batchId);
            $('#approve-payment-modal').modal('show');
        }

        function loadRejectionModal(id) {
            let batchId = id;
            $('#batchNoPayment').val(batchId);
            $('#reject-payment-modal').modal('show');
        }


        window.onload = function () {

            function refreshStatus(e) {
                let batch = $(e).data('batch-number')
                $.ajax({
                    url: '{{url("/disbursement/disbursement-status")}}/' + batch,
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
                                data.status === {{\App\Models\Batch::STATUS_ON_HOLD}} ||
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

            //retry confirmation modal
            setConfirmationModal($('.btn-retry-payment'), function (confirm, e) {
                if (confirm) {
                    $(location).attr('href', e.attr('href'));
                }
            }, "Warning", "Are you sure you want to retry to pay a previously failed payment");
        }
    </script>
@endsection
