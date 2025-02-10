@extends('layouts.master')

@section('content')
    <div class="container dashboard-cont">

        @if (session('pw-show-reminder', false))
            <div class="dashboard-cont-two">
                <div class="alert alert-warning alert-dismissable">
                    <div class="alert-rounded"> Your password is expiring in {{ session('pw-remaining-days', '%ERROR%') }}
                        days. Please change before expiry. <a href="{{ route('change.password') }}">Change now</a></div>
                </div>
            </div>
        @endif

        @cannot('has-approval-level')
            <div class="dashboard-cont-two">
                <div class="alert alert-danger alert-dismissable">
                    <div class="alert-rounded"> Your organization does not have approval level, Please contact administrator
                    </div>
                </div>
            </div>
        @endcannot
        @include('organization')
    </div>
@endsection
