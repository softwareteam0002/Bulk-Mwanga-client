@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('withdrawal-fees-create') }}

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
                <form method="post" action="{{url('withdrawal-fees/store')}}">

                    {{csrf_field()}}



                    <div class="form-body">
                        <label class="col-md-12"> Minimum Amount </label>
                        <div class="col-md-12 m-b-20">
                            <input type="number" step="0.01" class="form-control" min="0" name="min" required />
                        </div>
                        <label class="col-md-12"> Maximum Amount </label>
                        <div class="col-md-12 m-b-20">
                            <input type="number" step="0.01" class="form-control" min="0" name="max" required />
                        </div>
                        <label class="col-md-12"> Withdrawal fee</label>
                        <div class="col-md-12 m-b-20">
                            <input type="number" class="form-control" min="0" name="charge" required />
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <button type="submit" id="btn-save" class="btn col-md-2 btn-danger waves-effect">Save</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
