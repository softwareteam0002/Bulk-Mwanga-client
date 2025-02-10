@extends('layouts.master')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyModalLabel">Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to verify this batch?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="yesVerify" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container verif-page">

        <div class="row">

            <div class="col-md-12 col-sm-12">

                @include('partials.flash_error')

            </div>

            <div class="col-md-12 verif-breadcrumb">

                {{ \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('batches-for-verification') }}

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

                    @foreach ($orgaDisbursements as $index => $data)
                        <tr data-batch-number="{{ $data->batch_no }}">

                            @if ($index + 1 == 1 && $data->batch_status_id == \App\Models\Batch::STATUS_PENDING)
                                <td class="stat-row">{{ $index + 1 }}</td>
                            @else
                                <td>{{ $index + 1 }}</td>
                            @endif
                            <td>{{ $data->user_batch_no }}</td>
                            <td>{{ $data->total_amount }}</td>
                            <td>{{ $data->created_at }}</td>
                            <td class="status">
                                {{ \App\Models\Batch::getStatusName($data->batch_status_id) }}
                            </td>
                            <td>
                            </td>
                            <td>

                                @if ($data->batch_status_id == \App\Models\Batch::STATUS_PENDING)
                                    <!-- <a href="#"
                                            onclick="verifyBatch('{{ url('disbursement/mnp-search-request', $data->batch_no) }}')"
                                            class=" btn btn-sm btn-danger tooltip-voda fa fa-check">
                                            <span class="tooltip-text">Verify Batch</span>

                                        </a>-->
                                    <button type="button" data-batch-no="{{ $data->batch_no }}"
                                            class="btn btn-sm btn-danger tooltip-voda fa fa-check" data-toggle="modal"
                                            data-target="#verifyModal">

                                    </button>
                                @endif
                                <a href="{{ url('disbursement/view', encrypt($data->batch_no)) }}" class="tooltip-voda">
                                    <i class=" btn btn-sm btn-danger fa fa-eye"></i>
                                    <span class="tooltip-text">View Batch</span>

                                </a>

                                <a href="{{ route('download.batch', [encrypt($data->batch_no)]) }}"
                                   class="tooltip-voda">
                                    <i class="btn btn-sm btn-danger fa fa-download"></i>
                                    <span class="tooltip-text">Download Batch</span>

                                </a>

                                {{--                                    @endif --}}

                            </td>

                        </tr>
                    @endforeach


                    </tbody>
                </table>
                {{ $orgaDisbursements->links() }}
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
                    url: '{{url("/disbursement/mnp-search-status")}}/' + batch,
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
