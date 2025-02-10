@extends('layouts.master')



@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                {{ Breadcrumbs::render('roles') }}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))

                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"></a></p>
                    @endif
                @endforeach

                @if(\App\Models\Permission::canCreateRole())
                    <div>
                        <a href="{{url('/roles/create')}}" class="btn btn-danger btn-regi-organization">Create New
                            Role</a>
                    </div>
                @endif
                <table class="table table-striped table-bordered" id="table">

                    <thead>

                    <tr>

                        <th>No</th>
                        <th>Role Name</th>
                        <th>Actions</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($roles as $index=>$role)
                        <tr>

                            <td>{{$index+1}}</td>
                            <td>{{$role->name}}</td>

                            <td>
                                @if(\App\Models\Permission::canCreateRole())

                                    <a href="{{route('role-edit',$role->id)}}"
                                       class=" btn btn-danger fa fa-edit tooltip-voda">
                                        <span class="tooltip-text">Edit Role</span>

                                    </a>
                                @endif
                                <a href="{{url('roles/view',$role->id)}}"
                                   class=" btn btn-danger fa fa-eye tooltip-voda">
                                    <span class="tooltip-text">View Role</span>

                                </a>
                                @if(\App\Models\Permission::canCreateRole())

                                    <a href="#" id="{{$role->id}}"
                                       class=" btn btn-danger fa fa-trash role-delete tooltip-voda">
                                        <span class="tooltip-text">Delete Role</span>
                                    </a>

                                @endif
                            </td>

                        </tr>
                    @endforeach


                    </tbody>
                </table>


            </div>
        </div>

    </div>

    @include('roles.delete_role')

@endsection
