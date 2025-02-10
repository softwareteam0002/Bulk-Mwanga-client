
@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

    <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                @include('partials.flash_error')
                {{ Breadcrumbs::render('organization-details') }}

            </div>




          <div class="col-md-12">



          </div>


              <div class="col-md-6">

                  <table class="table table-bordered table-striped">

                      <tbody>
                      <tr style="background-color: #E60100; color: white;">


                          <td colspan="12">Details</td>
                      </tr>
                      <tr>
                          <th> Name</th><td>{{$organization->name}}</td>

                      </tr>
                      <tr>
                          <th>Short Code</th><td>{{$organization->short_code}}</td>

                      </tr>

                      <tr>
                          <th>Email</th><td>{{$organization->email}}</td>

                      </tr>

                      <tr>
                          <th> Phone Number</th><td>{{$organization->phone_number}}</td>

                      </tr>
                      <tr>
                          <th> Region</th><td>{{$organization['district']['region']->name}}</td>

                      </tr>

                      <tr>
                          <th> District</th><td>{{$organization['district']->name}}</td>

                      </tr>


                      </tbody>
                  </table>


              </div>
                      <div class="col-md-6">

                          <table class="table table-bordered table-striped">

                              <tbody>
                              <tr style="background-color: #E60100; color: white;">

                                  <td colspan="12">Actions</td>
                              </tr>
                              <tr>

                                  <th> Users </th><td><a href="{{url('organization/users-all',$organization->id)}}" class="btn btn-danger fa fa-users"></a> </td>

                              </tr>
                              <tr>
                                  <th> Users </th><td>
                                      <button type="button" id="{{$organization->id}}" class=" btn btn-danger organization-number-approval">

                                          Approvals <span class="badge badge-light numberl">{{$organization->number_approval}}</span>

                                      </button>                          </td>

                              </tr>

                              </tbody>
                          </table>


                      </div>

                @if(\Illuminate\Support\Facades\Auth::user()->user_type==2)

{{--              <div class="col-md-6">--}}

{{--                  <table class="table table-bordered table-striped">--}}

{{--                      <tbody>--}}
{{--                      <tr style="background-color: #E60100; color: white;">--}}

{{--                          <td colspan="12">Actions</td>--}}
{{--                      </tr>--}}
{{--                      <tr>--}}

{{--                          <th> Users </th><td><a href="{{url('organization/users-all')}}" class="btn btn-danger fa fa-users"></a> </td>--}}

{{--                      </tr>--}}
{{--                      <tr>--}}
{{--                          <th> Users </th><td>--}}
{{--                              <button type="button" id="{{$organization->id}}" class=" btn btn-primary organization-number-approval">--}}

{{--                                  Approvals <span class="badge badge-light numberl">{{$organization->number_approval}}</span>--}}

{{--                              </button>                          </td>--}}

{{--                      </tr>--}}

{{--                      </tbody>--}}
{{--                  </table>--}}


{{--              </div>--}}

        @endif

        <div class="col-md-12">

            <a href="{{route('organization.edit',$organizationId)}}" class="btn btn-danger fa fa-edit"></a>

            <a href="{{url('organization')}}" class="btn btn-danger">Back</a>

        </div>
        </div>

    </div>

    @include('organizations.approval_number_modal')

@endsection
