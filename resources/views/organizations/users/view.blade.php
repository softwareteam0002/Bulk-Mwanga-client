@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                @include('partials.flash_error')
                {{ Breadcrumbs::render('user-view') }}

            </div>


            <div class="col-md-6">

                <table class="table table-bordered table-striped">

                    <tbody>
                    <tr style="background-color: #E60100; color: white;">


                        <td colspan="12">Details</td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td>{{$user->first_name}}</td>

                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td>{{$user->last_name}}</td>

                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$user->email}}</td>

                    </tr>

                    <tr>
                        <th> Phone Number</th>
                        <td>{{$user->phone_number}}</td>

                    </tr>


                    </tbody>
                </table>
                @if(\App\Models\Permission::canCreateUser())

                    <a href="{{route('organization-user-edit',[$user->id,$user->organization_id])}}"
                       class=" btn btn-danger fa fa-edit tooltip-voda">
                        <span class="tooltip-text">Edit User</span>

                    </a>

                    <button type="submit" onclick="$('#password-resend-user').submit();"
                            class="btn btn-danger fa fa-repeat tooltip-voda">
                        <span class="tooltip-text">Reset user's Password</span>

                    </button>

                @endif
                <a href="{{url('organization/users-all',$user->organization_id)}}" class=" btn btn-danger">Back</a>
                <form action="{{url('users/password-resend')}}" id="password-resend-user" method="post"
                      class="form-horizontal">

                    {{csrf_field()}}
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="phoneNumber" value="{{$user->phone_number}}">


                </form>

            </div>
            <div class="col-md-6">

                <table class="table table-bordered table-striped">

                    <tbody>
                    <tr style="background-color: #E60100; color: white;">


                        <td colspan="12">Role & Status</td>
                    </tr>
                    <tr>
                        <th>Status</th>

                        <td>
                            @if($user->is_active==1)
                                Active
                            @elseif($user->is_active==0)
                                In Active
                            @endif
                        </td>

                    </tr>

                    @foreach($userRoles as $role)

                        @if($role->is_role_active==1)
                            <tr>
                                <th>Role Name</th>
                                <td>{{$role->name}}

                                    @if($role->is_delegated==1)
                                        <span style="margin-left: 3px; color: #e60100">[Delegated]</span>
                                    @endif
                                </td>
                            </tr>
                        @endif

                    @endforeach


                    </tbody>
                </table>


            </div>

        </div>

    </div>

@endsection
