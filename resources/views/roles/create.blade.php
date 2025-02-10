
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
                <form method="post" action="{{url('roles/store')}}">

                    {{csrf_field()}}



                    <div class="form-body">


                        <div class="col-md-12">

                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="role-name">Role Name</label>

                                        <input type="text" name="name" id="role-name" class="form-control" value="{{old('name')}}">

                                    </div>
                                </div>
                                <!--/span-->
                                @if(\Illuminate\Support\Facades\Auth::user()->user_type==1)
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            <div class="form-group">
                                                <label class="control-label">Role Type</label>


                                                <select class="form-control" name="roleType">

                                                    <option value="2">Organization</option>
                                                    <option value="1">Vodacom</option>

                                                </select>
                                            </div>
                                        </div>


                                    </div>

                            @endif
                            <!--/span-->
                            </div>


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

                                    @if($index<7)
                                    <li>
                                        <span class="perm-role-span"><input type="checkbox" name="permission[]" class="checkbox-custom" value="{{$permission->id}}"> {{$permission->name}} </span>
                                    </li>

                                    @endif
                                @endforeach

                            </ul>
                        </div>

                        <div class="col-md-4">

                            <ul class="rol-perm-list">
                                @foreach($permissions as  $index=>$permission)

                                    @if($index>=7)

                                    <li>
                                        <span class="perm-role-span"><input type="checkbox" name="permission[]" class="checkbox-custom" value="{{$permission->id}}"> {{$permission->name}} </span>
                                    </li>

                                    @endif
                                @endforeach

                            </ul>
                        </div>

                    </div>

                <div class="col-md-6">

                    <div class="form-group">

                 <button class="btn btn-danger">Save</button>

                        <a href="{{url('roles')}}" class="btn btn-danger">Back</a>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
