

@extends('layouts.master')


@section('content')



    <div class="container" style="background-color: white; margin-top: 20px">


        <div class="row">

            <div class="col-md-12">
                {{--               <p style="color: #E60100; font-size: 20px;font-weight: 400">Register Organization</p>--}}

                {{ Breadcrumbs::render('organization-create') }}

            </div>

            <div class="col-md-12">

                @include('partials.flash_error')

            </div>
        </div>

        <!-- Row -->
        <div class="row div-animate-form" style="width: 10%;">
            <div class="col-lg-12">
                <div class="card card-outline-info">

                    {{--                    <div class="card-block">--}}
                    <form action="{{url('organization/update',$organizationId)}}" method="post">

                       {{csrf_field()}}


                        <div class="form-body">

                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Organization Name</label>
                                        <input   type="text" name="name" id="name" class="form-control error" {{old('name')}} value="{{$organization->name}}"
                                        data-validation="alphanumeric"   data-validation-error-msg="Only Alphabet and number are allowed"
                                               data-validation-allowing=" ">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="control-label">Select Region</label>
                                        <select class="form-control custom-select region" name="region" {{old('region')}}>

                                            @foreach($regions as $index=>$region)

                                                <option value="{{$region['id']}}"

                                                      @if($region['id']===$organization->region_id)
                                                       selected
                                                        @endif

                                                >{{$region['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shortCode" class="control-label">Short Code</label>
                                        <input readonly type="text" name="shortCode" id="shortCode" class="form-control" {{old('shortCode')}}
                                        data-validation="number" data-validation-allowing="range[0;1000000]"
                                               data-validation-error-msg="Invalid Code"
                                               value="{{$organization->short_code}}"
                                        >

                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district" class="control-label">Select District</label>
                                        <select class="form-control custom-select district" id="district" name="district">

                                            <option value="{{$organization->district_id}}">{{$organization['district']->name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label  for="email" class="control-label">Email</label>

                                        <input  data-validation="email"  type="text" id="email" class="form-control error" name="email" {{old('email')}} required
                                               data-validation-error-msg="Invalid Email"
                                               value="{{$organization->email}}"

                                        >

                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="control-label">Phone Number</label>
                                        <input type="text"  {{old('phone_number')}}id="phone_number" name="phone_number" class="form-control"
                                               value="{{$organization->phone_number}}"
                                               data-validation="custom" data-validation-regexp="^([0-9,+]+)$" data-validation-error-msg="Invalid Phone Number"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{--                            </div>--}}
                        <div class="form-actions">
                            <button type="submit" class="btn btn-danger"> <i class="fa fa-check"></i> Save</button>
                            <a href="{{url('')}}" class="btn btn-danger">Back</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    </div>

    <script>
        var url = '{{ route('districts.get') }}';

    </script>

@endsection
