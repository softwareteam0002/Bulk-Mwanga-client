@extends('layouts.master')


@section('content')

    <div class="container">


        <div class="row">

            <div class="col-md-12">
                {{--               <p style="color: #E60100; font-size: 20px;font-weight: 400">Register Organization</p>--}}

                {{ Breadcrumbs::render('organization-create') }}

            </div>

            <div class="col-md-12 " style="margin-top: 10px;">
                @include('partials.flash_error')

            </div>
        </div>

        <!-- Row -->
        <div class="row div-animate-form" style="width: 10%;">
            <div class="col-lg-12">
                <div class="card card-outline-info">

                    {{--                    <div class="card-block">--}}

                    <div class="form-body">
                        <div class="row p-t-20">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" required
                                           placeholder="Enter Short Code To Fetch Organization Details" name="name"
                                           id="name" class="form-control error" {{old('name')}}
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <button class="btn btn-danger">Fetch Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{url('organization/store')}}" method="post">

                        {{csrf_field()}}


                        <div class="form-body">

                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Organization Name</label>
                                        <input type="text" name="name" id="name" class="form-control error"
                                               {{old('name')}}
                                               required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="control-label">Region</label>
                                        <select required class="form-control  custom-select region"
                                                name="region" {{old('region')}}>

                                            <option selected disabled>Please select</option>
                                            @foreach($regions as $region)

                                                <option value="{{$region['id']}}">{{$region['name']}}</option>
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
                                        <input type="text" name="shortCode" id="shortCode" class="form-control"
                                               {{old('shortCode')}}
                                               required
                                        >

                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="district" class="control-label">Select District</label>
                                        <select required class="form-control custom-select district" id="district"
                                                name="district">

                                        </select>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="email" class="control-label">Email</label>

                                        <input required type="text" id="email" class="form-control error"
                                               name="email" {{old('email')}}
                                        >

                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="control-label">Phone Number</label>
                                        <input type="text" {{old('phone_number')}}id="phone_number" name="phone_number"
                                               class="form-control"

                                               data-validation="custom" data-validation-regexp="^([0-9,+]+)$"
                                               data-validation-error-msg="Invalid Phone Number"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{--                            </div>--}}
                        <div class="form-actions">
                            <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Save</button>

                            <a href="{{url()->previous()}}" class="btn btn-danger">Back</a>
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
