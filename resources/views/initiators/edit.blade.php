
@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('user-create') }}

                </div>
            </div>

            <div class="col-md-12">

                <div class="col-md-12">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))

                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a></p>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
        <form method="post" action="{{url('/initiator/update',$initiator->id)}}">

            <div class="row">

                <div class="col-md-12">


                    {{csrf_field()}}


                    <div class="col-md-12">

                        <!-- Row -->
                        <div class="row div-animate-form" style="width: 10%;">
                            <div class="col-lg-12">
                                <div class="card card-outline-info">

                                    {{--                    <div class="card-block">--}}

                                    <div class="form-body">

                                        <div class="row p-t-20">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">User Name</label>
                                                    <input required type="text" id="username" name="username" value="{{$initiator->username}}" class="form-control">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label class="control-label">Password</label>
                                                        <input required type="text"  name="password" id="password"   class="form-control">
                                                    </div>
                                                </div>


                                            </div>

                                            <input value="{{encrypt($initiator->organization_id)}}" name="organizationId" type="hidden">

                                            <div class="col-md-12">
                                                <div class="form-group">

                                                    <div class="form-group">

                                                        <button class="btn btn-danger">Update</button>
                                                    </div>
                                                </div>


                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                        <!--/span-->

                                        <!--/span-->
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Row -->

        </form>


    </div>
    </div>

@endsection
