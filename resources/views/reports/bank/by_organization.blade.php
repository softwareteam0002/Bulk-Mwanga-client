
@extends('layouts.master')

@section('content')

    <div class="container custom-report-container" >

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('roles-create') }}

                </div>
            </div>

        </div>
        <form method="post" action="{{url('reports/disbursement-per-organization')}}">

            {{csrf_field()}}
        <div class="row">

            <div class="col-md-12">
{{--                @foreach (['danger', 'warning', 'success', 'info'] as $msg)--}}
{{--                    @if(Session::has('alert-' . $msg))--}}

{{--                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}--}}
{{--                            <a href="#" class="close" data-dismiss="alert" aria-label="close"></a></p>--}}
{{--                    @endif--}}
{{--                @endforeach--}}


{{--                    <div class="form-body">--}}

                        <div class="col-md-12">

                            @include('partials.flash_error')

                            <div class="row p-t-20">

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <input placeholder="Short Code" type="text" name="shortCode" id="shortCode-name" class="form-control d-input" value="{{old('name')}}">

                                    </div>

                            </div>
                                <div class="col-md-3">

                                    <div class="form-group">

                                        <input placeholder="Start Date" type="text" name="startDate" id="role-name" class="form-control d-input" value="{{old('name')}}">

                                    </div>

                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">

                                        <input placeholder="End Date" type="text" name="endDate" id="role-name" class="form-control datepicker-input" value="{{old('name')}}">

                                    </div>

                                </div>
                            </div>
                        </div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <button class="btn btn-danger" name="excel">Excel</button>
                           <!-- <button class="btn btn-danger" name="pdf">PDF</button>-->

                        </div>

                    </div>

                    </div>


            </form>
{{--            </div>--}}
        </div>
    </div>

@endsection
