@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                {{ Breadcrumbs::render('transaction-charges') }}

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

                @if(\App\Models\Permission::canCreateWithdrawalFees())
                    <div>
                        <a href="{{url('/transaction-charges/create')}}" class="btn btn-danger btn-regi-organization">Add
                            New Transaction Charge</a>
                    </div>
                @endif
                <table class="table table-striped table-bordered" id="table">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Charges</th>
                        <th>Updated</th>
                        <th class="notexport">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($charges as $i => $transaction_charge)
                        <tr>
                            <td class="col1">{{ $i+1 }}</td>
                            <td>{{$transaction_charge->min_amount}}</td>
                            <td>{{$transaction_charge->max_amount}}</td>
                            <td>{{$transaction_charge->charge}}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction_charge->updated_at)->diffForHumans() }}</td>
                            <td>
                                @if(\App\Models\Permission::canCreateWithdrawalFees())
                                    <a href="{{route('transaction_charges.edit',$transaction_charge->id)}}">
                                        <button id="{{$transaction_charge->id}}" class="btn btn-sm btn-danger" href="#"
                                                title="Edit transaction charge"><i class="fa fa-edit"></i>Edit
                                        </button>
                                    </a>
                                    <form id="{{$transaction_charge->id}}"
                                          action="{{ url('transaction-charges/delete', $transaction_charge->id)}}"
                                          class="delete-service-form" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{$transaction_charge->id}}">
                                        <button class="btn btn-sm btn-danger btn-delete" type="submit"
                                                title="Delete transaction charge"><i class="fa fa-trash "></i>Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>


            </div>
        </div>

    </div>

    @include('roles.delete_role')

@endsection
@section('scripts')
    <script>
        window.onload = function () {
            //retry confirmation modal
            setConfirmationModal($('.btn-delete'), function (confirm, e) {
                if (confirm) {
                    e.parent().submit();
                }
            }, "Warning", "Are you sure you delete this entry?");
        }
    </script>
@endsection
