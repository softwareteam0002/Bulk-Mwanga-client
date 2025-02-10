@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

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
        <form method="post" action="{{url('users/store')}}">

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
                                                    <label class="control-label">First Name</label>
                                                    <input required type="text" id="firstName" name="firstName"
                                                           value="{{old('firstName')}}" class="form-control">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">

                                                    <div class="form-group">
                                                        <label class="control-label">Last Name</label>
                                                        <input required type="text" name="lastName" id="lastName"
                                                               value="{{old('lastName')}}" class="form-control">
                                                    </div>
                                                </div>


                                            </div>

                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input required type="email" name="email" id="email"
                                                           value="{{old('email')}}" class="form-control">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label">Phone Number</label>
                                                    <input required type="text" name="phoneNumber"
                                                           value="{{old('phoneNumber')}}" id="phone_number"
                                                           class="form-control">
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">

                                                    <label class="control-label">Username</label>
                                                    <input required type="text" name="username"
                                                           value="{{old('username')}}" id="username"
                                                           class="form-control">

                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label">Send OTP Via Email</label>

                                                    <br>
                                                    <input type="checkbox"
                                                           value="{{\App\Models\ConstantHelper::SEND_OTP_BY_EMAIL}}"
                                                           class="checkbox-custom" style="width: 90px; height: 23px;">
                                                </div>

                                            </div>

                                        </div>

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


            <div class="col-md-12">

                <table class="table table-striped table-bordered" id="table">

                    <tbody>

                    <tr>

                        <td colspan="12" style="background-color: #E60100;color: white;">Select Role(s)</td>

                    </tr>

                    </tbody>

                </table>
            </div>


            <div class="col-md-12">

                <div class="col-md-4" style="margin: 0;">

                    <ul class="rol-perm-list">
                        @foreach($roles as  $index=>$role)

                            @if($index<4)
                                <li>
                                    <span class="perm-role-span"><input type="checkbox" name="role[]"
                                                                        class="checkbox-custom" value="{{$role->id}}"> {{$role->name}} </span>
                                </li>

                            @endif
                        @endforeach

                    </ul>
                </div>

                <div class="col-md-4">

                    <ul class="rol-perm-list">
                        @foreach($roles as  $index=>$role)

                            @if($index>=4)

                                <li>
                                    <span class="perm-role-span"><input type="checkbox" name="role[]"
                                                                        class="checkbox-custom" value="{{$role->id}}"> {{$role->name}} </span>
                                </li>

                            @endif
                        @endforeach

                    </ul>
                </div>


                {{--                    <div class="col-md-6">--}}

                <div class="form-group">

                    <button class="btn btn-danger" type="submit">Save</button>
                    <a href="{{url('users')}}" class="btn btn-danger">Back</a>

                </div>

                {{--                    </div>--}}


            </div>

    </div>
    </form>
    </div>

@endsection
