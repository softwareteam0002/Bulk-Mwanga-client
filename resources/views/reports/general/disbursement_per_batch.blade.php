
@extends('layouts.master')

@section('content')

    <div class="container custom-report-container">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('reports-per-batch') }}

                </div>
            </div>

        </div>
        <form method="post" action="{{url('reports/bank/disbursement-per-batch')}}">

            {{csrf_field()}}
            <div class="row">

                <div class="col-md-12">

                    <div class="col-md-12">
                        @include('partials.flash_error')

                    </div>

                    <div class="row p-t-20">
                        <div class="col-md-3">

                            <div class="form-group">


                                <input placeholder="Batch Number" type="text" name="batchNumber" id="role-name"
                                       class="form-control d-input"
                                       @if(!empty($batchPayment->user_batch_no))

                                       value="{{$batchPayment->user_batch_no}}"

                                       @endif

                                >


                            </div>

                        </div>


                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <button class="btn btn-danger" type="submit">Get</button>

                    </div>

                </div>

            </div>


        </form>


        @if(!empty($batchPayment))

            @include('reports.bank.organization_batch_report',compact('batchPayment'))
            @include('reports.bank.batch_report',compact('batchPayment'))

            @else

            <table class="table custom-table">
                <tbody>
                <tr>
                    <td colspan="12">No Result Found</td>
                </tr>
                </tbody>
            </table>
        @endif
    </div>


@endsection
