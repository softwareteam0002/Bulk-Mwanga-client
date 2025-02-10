

@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px; width: 100%; margin-right:0;">

        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                @include('partials.flash_error')
                <div class="card card-outline-info">
                    <div class="card-headere">
                        {{--                        <h4 class="m-b-0 text-white">File upload</h4>--}}
                        {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('disbursement-create')}}
                    </div>

                    <div>
                        <form action="{{url('help/download/format')}}" method="post" class="pull-left">
                            @csrf
{{--                            <button type="submit" class="btn btn-danger" name="mno" style="margin-top: 10px; margin-bottom: 10px;"> Mobile money Template</button>--}}
                            <button type="submit" class="btn btn-danger" name="bank" style="margin-top: 10px; margin-bottom: 10px;"> Bank template</button>

                        </form>
                        <a href="#" class="float-left" id="btn-check-balance">
                            <button class="btn btn-danger pull-left" style="margin-top: 10px; margin-bottom: 10px;margin-left: 12px">Check balance</button>
                        </a>
                    </div>

                    {{--                    <div class="card-block">--}}
                    <form action="{{url('bank-disbursement/store')}}" method="post" enctype="multipart/form-data">

                        {{csrf_field()}}

                        @can('is-org-deleted')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select required class="form-control" name="uploadType" id="uploadType">
                                                <option selected disabled>Select Payment Stage</option>
                                                <option value="1">Verification </option>
                                                <option value="2">Make Payment</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select required readonly class="form-control" name="transaction_type" id="transaction_type">
{{--                                                <option selected disabled>Transaction type</option>--}}

                                                @foreach($transactionType as $row)

                                                    @if($row->id==2)
                                                    <option value="{{$row->id}}"
                                                            selected
                                                    >{{$row->name}}</option>

                                                    @endif

                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group input-group">
                                            <input  type="text" id="userBatchNumber" class="form-control" placeholder="Enter Batch Number (Optional)" name="batchNo" style="display: none">
                                            <input  type="text" id="paymentDescription" class="form-control" placeholder="Enter batch description" name="description" style="display: none;">
                                        </div>
                                    </div>
                                </div>
								
								
                                <div class="row" id="payment-schedule-row" style="display: none;">
								
								
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" name="payment_time" id="payment-time">
                                                <option selected disabled value="0">Select when do you want to make payment</option>
                                                <option value="1">Pay immediately</option>
                                                <option value="2">Schedule</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="scheduled-at" style="display: none">
                                        <div class="form-group input-group">
                                            <input  type="text" class="form-control d-input" placeholder="Click to pick date" name="scheduled_at">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <input  required  data-validation-allowing="jpg, png, gif" id="input-b1"  accept=".xls,.xlsx" name="file" type="file" class="file" data-browse-on-zone-click="true" data-show-upload="false" data-show-caption="true">
                                    </div>
                                </div>




                            </div>
                            <div class="form-actions" style="margin-top: 10px;">
                                <button type="submit" class="btn btn-success">Upload</button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->


    <div class="modal balance-model fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="balance-model">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ffffff;">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel" style="color: #ffffff;">Account Balance</h4>
                </div>
                <div class="modal-body">
                    <div style="text-align: center" id="spinner">
                        <span class="fa fa-spinner fa-spin fa-3x" style="width: 45px;"></span>
                        <br>
                        <h5>Checking balance...</h5>
                    </div>
                    <div style="text-align: center">
                        <h5 id="balance">.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="application/javascript">
        var formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'Tsh',
            minimumFractionDigits: 0
        });

        window.onload = function () {
            $("#uploadType").on('change',function () {
                if($('#uploadType option:selected').val()==1){
                    $("[name='batchNo']").show()
                    $("[name='description']").hide()
                    $("#payment-schedule-row").hide()
                }else{
                    $("[name='batchNo']").hide()
                    $("[name='description']").show()
                    $("#payment-schedule-row").show()
                }
            })

            $("#payment-time").on('change',function () {
                if($('#payment-time option:selected').val()==2){
                    $("#scheduled-at").show()
                }else{
                    $("#scheduled-at").hide()
                }
            })

            //date picker
            let options={
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                minDate: 0,
                maxDate:30
            };
            $("[name='scheduled_at']").datepicker(options);

            //Balance check
            var limitCounter = 0;
            var modalHidden = true;
            $('#btn-check-balance').on('click',function () {

                $('#balance-model #spinner').show()
                $('#balance-model #balance').hide()
                $('#balance-model #balance').html("Checking balance!")
                $('#balance-model').modal('show');
                modalHidden = false;
                limitCounter = 0;

                $.ajax({
                    method: "POST",
                    url: "{{url('/disbursement/query-balance')}}",
                    data:{ "_token": "{{ csrf_token() }}"},
                }).done(function( result ) {
                    if(result.status === 'success'){
                        checkBalanceAvailability(result.tx_id);
                    }else{
                        $('#balance-model #spinner').hide()
                        $('#balance-model #balance').show()
                        $('#balance-model #balance').html("Response:"+result.message)
                    }
                }).fail(function (e) {
                    $('#balance-model #spinner').hide()
                    $('#balance-model #balance').show()
                    $('#balance-model #balance').html("Failed to query balance!")
                });
            });

            $('#balance-model').on('hidden.bs.modal', function (e) {
                modalHidden = true;
            })

            function checkBalanceAvailability(txId) {
                if(modalHidden){
                    console.log("Model hidden");
                    return;
                }
                $.ajax({
                    method: "GET",
                    url: "{{url('/disbursement/check-balance-availability')}}/"+txId,
                }).done(function( result ) {
                    if(result.status === 'success'){
                        var MAX = 8;
                        if(result.rq_status === 'success'){
                            $('#balance-model #spinner').hide()
                            $('#balance-model #balance').show()
                            $('#balance-model #balance').html(formatter.format(result.balance.available_balance))
                        }else if(result.rq_status === 'pending' && limitCounter<=MAX){
                            limitCounter++
                            setTimeout(checkBalanceAvailability,5000,txId)
                        }else if (limitCounter>MAX){
                            $('#balance-model #spinner').hide()
                            $('#balance-model #balance').show()
                            $('#balance-model #balance').html('Failed to query balance : Taking too long!')
                        }else{
                            $('#balance-model #spinner').hide()
                            $('#balance-model #balance').show()
                            $('#balance-model #balance').html('Failed to query balance!')
                        }
                    }else{
                        $('#balance-model #spinner').hide()
                        $('#balance-model #balance').show()
                        $('#balance-model #balance').html('Response: '+result.message)
                    }
                });
            }
        }
    </script>
@endSection
