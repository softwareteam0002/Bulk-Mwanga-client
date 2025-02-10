@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                <div class="col-md-12">

                    {{ Breadcrumbs::render('withdrawal-fees-update') }}

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

                    <form method='post' action="{{ route('withdrawal_fees.update', $charge->id) }}">
                        <from class="form-horizontal">
                            @method('POST')
                            @csrf
                            <div class="form-body">
                                    <label class="col-md-12"> Enter the Minimum Amount </label>
                                    <div class="col-md-12 m-b-20">
                                        <input type="number" step="0.01" class="form-control" value="{{ $charge->min_amount }}"min="0" name="min" required />
                                    </div>
                                    <label class="col-md-12"> Enter the Maximum Amount </label>
                                    <div class="col-md-12 m-b-20">
                                        <input type="number" step="0.01" class="form-control" value="{{ $charge->max_amount }}" min="0" name="max" required />
                                    </div>
                                    <label class="col-md-12"> Enter Withdrawal Charge</label>
                                    <div class="col-md-12 m-b-20">
                                        <input type="number" class="form-control" value="{{ $charge->fee }}" min="0" name="charge" required />
                                    </div>
                            </div>

                        </from>
                        <button type="submit" id="btn-save" class="btn col-md-2 btn-danger pull-right waves-effect">Save</button>
                    </form>
                    </form>

            </div>
        </div>
    </div>

@endsection
