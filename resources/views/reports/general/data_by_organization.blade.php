
@extends('layouts.master')

@section('content')

    <div class="container custom-report-container">

        <div class="row">

            <div class="col-md-12">

                <table class="table table-bordered table-striped">

                    <thead>

                    <tr>

                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                        <th>Amount</th>
                        <th>Zone</th>
                        <th>Date</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($data as $index=>$d)

                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$d->first_name}}</td>
                            <td>{{$d->last_name}}</td>
                            <td>{{$d->phone_number}}</td>
                            <td>{{$d->amount}}</td>
                            <td>{{$d->zone}}</td>
                            <td>{{$d->payment_date}}</td>

                        </tr>

                    @endforeach

                    </tbody>
                </table>



            </div>

        </div>

    </div>

@endsection
