@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message')
    Page Expired -
    <a href="{{url('/')}}" style="color: red; text-decoration: none;font-weight: bold">GO HOME</a>
@endsection
