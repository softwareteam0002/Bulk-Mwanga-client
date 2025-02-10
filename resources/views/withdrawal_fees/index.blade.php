@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin">

                {{ Breadcrumbs::render('withdrawal-fees') }}

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
                        <a href="{{url('/withdrawal-fees/create')}}" class="btn btn-danger btn-regi-organization">Add
                            New Withdrawal Fee</a>
                    </div>
                @endif
                <table class="table table-striped table-bordered" id="table">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Fee</th>
                        <th>Updated</th>
                        <th class="notexport">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($charges as $i => $withdrawal_fee)
                        <tr>
                            <td class="col1">{{ $i+1 }}</td>
                            <td>{{$withdrawal_fee->min_amount}}</td>
                            <td>{{$withdrawal_fee->max_amount}}</td>
                            <td>{{$withdrawal_fee->fee}}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $withdrawal_fee->updated_at)->diffForHumans() }}</td>
                            <td>
                                @if(\App\Models\Permission::canCreateWithdrawalFees())
                                    <a href="{{route('withdrawal_fees.edit',$withdrawal_fee->id)}}">
                                        <button id="{{$withdrawal_fee->id}}" class="btn btn-sm btn-danger" href="#"
                                                title="Edit withdrawal fee"><i class="fa fa-edit"></i>Edit
                                        </button>
                                    </a>
                                    <form id="{{$withdrawal_fee->id}}"
                                          action="{{ url('withdrawal-fees/delete', $withdrawal_fee->id)}}"
                                          class="delete-service-form" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{$withdrawal_fee->id}}">
                                        <button class="btn btn-sm btn-danger btn-delete" type="submit"
                                                title="Delete withdrawal fee"><i class="fa fa-trash "></i>Delete
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
