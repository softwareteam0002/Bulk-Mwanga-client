@extends('layouts.master')



@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                {{ Breadcrumbs::render('users') }}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                @include('partials.flash_error')
                @if(\App\Models\Permission::canCreateUser())
                    <div>
                        <a href="{{url('/users/create')}}" class="btn btn-danger btn-regi-organization">Create New
                            User</a>
                    </div>

                @endif
                <table class="table table-striped table-bordered " id="table">

                    <thead>

                    <tr>

                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Status</th>
                        <th>Actions</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($users as $index=>$user)
                        <tr>

                            <td>{{$index+1}}</td>
                            <td>{{$user->first_name}}</td>
                            <td>{{$user->last_name}}</td>
                            <td>
                                @if($user->is_active==1)
                                    Active

                                @elseif($user->is_active==0)

                                    Inactive
                                @endif

                            </td>

                            <td>
                                @if(\App\Models\Permission::canCreateUser())

                                    <a href="{{route('user-edit',$user->id)}}"
                                       class=" btn btn-danger  fa fa-edit tooltip-voda">

                                        <span class="tooltip-text">Edit User</span>

                                    </a>
                                @endif
                                <a href="{{url('users/view',encrypt($user->id))}}"
                                   class=" btn btn-danger fa fa-eye tooltip-voda">
                                    <span class="tooltip-text">View User</span>
                                </a>
                                @if(\App\Models\Permission::checkIfIsChecker())

                                    @if($user->is_active===1)
                                        <a href="#" id="{{$user->id}}"
                                           class=" btn btn-danger fa fa-lock user-deactivate tooltip-voda">
                                            <span class="tooltip-text">Deactivate User</span>

                                        </a>
                                    @elseif($user->is_active===0)
                                        <a href="#" id="{{$user->id}}"
                                           class=" btn btn-danger icon-lock-open   user-activate tooltip-voda">
                                            <span class="tooltip-text">Activate User</span>

                                        </a>
                                    @endif
                                @endif
                            </td>

                        </tr>

                    @endforeach

                    </tbody>
                </table>


            </div>
        </div>

    </div>

    @include('users.deactivate_user')
    @include('users.activate_user')

@endsection
