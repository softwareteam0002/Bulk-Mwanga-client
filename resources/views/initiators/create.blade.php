
@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('initiator-create') }}

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
        <form method="post" action="{{url('/initiator/store')}}">

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
                                                    <input required type="text" id="username" name="username" value="{{old('username')}}" class="form-control" placeholder="Username">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">

                                                    <div class="form-group">
                                                        <input required type="text" name="password" id="password" value="{{old('password')}}" class="form-control" placeholder="Password">
                                                    </div>
                                                </div>


                                            </div>
                                            <input type="hidden"  name="organizationId" value="{{encrypt($organizationId)}}">

                                            <div class="col-md-12">
                                                <div class="form-group">

                                                    <div class="form-group">

                                                        <button class="btn btn-danger">Save</button>
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
