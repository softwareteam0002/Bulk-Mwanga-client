@extends('layouts.master')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                @include('partials.flash_error')
                {{ Breadcrumbs::render('organization') }}

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                @if(\App\Models\Permission::canCreateOrganization())
                    <div>
                        <a href="{{url('/organization/create')}}" class="btn btn-danger btn-regi-organization">Register
                            new organization</a>
                    </div>
                @endif
                <table class="table table-striped table-bordered" id="table">

                    <thead>

                    <tr>

                        <th>No</th>
                        <th>Short code</th>
                        <th>Name</th>
                        <th>Region</th>
                        <th>District</th>
                        <th>Zone</th>
                        <th>Actions</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($organizations  as $index=>$data)
                        <tr>

                            <td>{{$index+1}}</td>
                            <td>{{$data->short_code}}</td>
                            <td>{{$data->name}}</td>
                            <td>{{$data->rname}}</td>
                            <td>{{$data->dname}}</td>
                            <td>{{$data->zone}}</td>
                            <td>
                                @if(\App\Models\Permission::canCreateOrganization())

                                    <a href="{{route('organization.edit',$data->id)}}"
                                       class=" btn btn-danger fa fa-edit tooltip-voda">
                                        <span class="tooltip-text">Edit Organization</span>

                                    </a>
                                @endif
                                <a href="{{url('organization/view',$data->id)}}"
                                   class=" btn btn-danger fa fa-eye tooltip-voda">

                                    <span class="tooltip-text">View Organization</span>

                                </a>


                                @if(\App\Models\Permission::checkIfIsChecker())
                                    @if($data->status==1)
                                        <a href="#" id="{{$data->id}}" class=" btn btn-danger disable-organization da">
                                            Deactivate</a>

                                    @elseif($data->status==0)
                                        <a href="#" id="{{$data->id}}" class=" btn btn-danger enable-organization da">
                                            activate </a>

                                    @endif

                                @endif
                                <a href="{{url('organization/users-all',$data->id)}}"
                                   class=" btn btn-danger fa fa-users tooltip-voda">
                                    <span class="tooltip-text">Organization Users</span>

                                </a>

                                @if(\App\Models\Permission::canCreateOrganization())

                                    <button type="button" id="{{$data->id}}"
                                            class=" btn btn-danger organization-number-approval">

                                        Approvals <span
                                            class="badge badge-light numberl">{{$data->number_approval}}</span>

                                    </button>
                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>

    </div>

    @include('organizations.deactivate_modal')
    @include('organizations.activate_modal')
    @include('organizations.approval_number_modal')

@endsection
