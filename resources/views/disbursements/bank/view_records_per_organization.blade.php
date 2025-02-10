

@extends('layouts.master')


@section('content')


    <div class="container" style="background-color: white; margin-top: 20px">


        <!-- Row -->
        <div class="row" >
            <div class="col-lg-12 col-sm-12 col-md-12">
                <div class="card card-outline-info">
                    <div class="card-headers" >
{{--                        <h4 class="m-b-0 text-white">View Uploaded data</h4>--}}

                        {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('disbursement-view')}}

                    </div>
{{--                    <div class="card-block">--}}
                        <form action="#">
                            <div class="form-body">

                                <div class="row p-t-20">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Enter Short Code or Organization Name</label>
                                            <input type="text" id="batch_no" class="form-control">
                                        </div>
                                    </div>

                                    <!--/span-->
                                </div>


                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Get</button>
                            </div>
                        </form>
{{--                    </div>--}}
                </div>
            </div>
        </div>
        <!-- Row -->
    </div>

@endsection
