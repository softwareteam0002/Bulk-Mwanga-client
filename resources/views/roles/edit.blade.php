
@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('roles-create') }}

                </div>
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
                <form method="post" action="{{url('roles/update',$roleId)}}">

                    {{csrf_field()}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="role-name">Name</label>

                            <input type="text" name="name" id="role-name" class="form-control" value="{{$role->name}}">

                        </div>

                    </div>

                    <div class="col-md-12">

                        <table class="table table-striped table-bordered" id="table">

                            <tbody>

                            <tr>

                                <td colspan="12" style="background-color: #E60100;color: white;">Select Permissions</td>

                            </tr>

                            </tbody>

                        </table>
                    </div>

                    <div class="row">

                        <div class="col-md-4">

                            <ul class="rol-perm-list">
                                @foreach($permissions as  $index=>$permission)

                                    @if($index<4)
                                        <li>
                                        <span class="perm-role-span"><input type="checkbox" name="permission[]" class="checkbox-custom" value="{{$permission->id}}"

                                                                            @foreach($rolePermissions as $rolep)

                                                                            @if($permission->id==$rolep->permission_id)

                                                                            checked
                                                    @endif
                                                @endforeach

                                            > {{$permission->name}} </span>
                                        </li>

                                    @endif
                                @endforeach

                            </ul>
                        </div>

                        <div class="col-md-4">

                            <ul class="rol-perm-list">
                                @foreach($permissions as  $index=>$permission)

                                    @if($index>=4)

                                        <li>
                                        <span class="perm-role-span"><input type="checkbox" name="permission[]" class="checkbox-custom" value="{{$permission->id}}"
                                                                            @foreach($rolePermissions as $rolep)

                                                                            @if($permission->id==$rolep->permission_id)

                                                                            checked
                                                    @endif
                                                @endforeach

                                            > {{$permission->name}} </span>
                                        </li>

                                    @endif
                                @endforeach

                            </ul>
                        </div>

                    </div>

                    <div class="col-md-6" style="margin-top: 10px;">

                        <div class="form-group">

                            <button class="btn btn-danger">Save</button>
                            <a href="{{url()->previous()}}" class="btn btn-danger">Back</a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
