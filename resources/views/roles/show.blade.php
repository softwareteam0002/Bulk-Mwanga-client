
@extends('layouts.master')



@section('content')


    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                {{ Breadcrumbs::render('roles-view') }}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <table class="table table-striped table-bordered" id="table">


                    <tbody>



                    </tbody>
                </table>

                <table class="table table-striped table-bordered table-search" id="table">

                    <thead>

                    <tr style="background-color: #E60100;">

                        <th colspan="12" style="color: white;">Role Name  <b style="margin-left: 10px;"> {{$role->name}}</b></th>


                    </tr>

                    <tr>
                        <th>No</th>
                        <th>Permission Name</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($permissions as $index=>$permission)
                        <tr>

                            <td>{{$index+1}}</td>
                            <td>{{$permission->name}}</td>


                        </tr>

                    @endforeach

                    </tbody>
                </table>

                <a href="{{url('roles')}}" class="btn btn-danger">Back</a>
            </div>
        </div>

    </div>

@endsection
