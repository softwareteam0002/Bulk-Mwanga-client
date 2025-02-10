@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 breadcrumb-margin">
                {{ Breadcrumbs::render('initiator') }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials.flash_error')

                @if(\App\Models\Permission::createInitiator())
                    @if(!$initiator)
                        <div class="mb-3">
                            <a href="{{ route('initiator-create', ['q' => 'adin', 'orid' => encrypt($id)]) }}"
                               class="btn btn-danger"><i class="fa fa-plus"></i> Create New Initiator</a>
                        </div>
                    @endif
                @endif

                <div class="card">
                    <div class="pl-4">
                        <h4 class="mb-0 para">Initiator Details</h4>
                    </div>

                    <div class="card-body">
                        @if($initiator)
                            <table class="table table-bordered table-striped">
                                <tbody>
                                <tr>
                                    <td><strong>Username</strong></td>
                                    <td>{{ $initiator->username }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Password</strong></td>
                                    <td>
                                        <input type="password" id="passwordField" class="form-control"
                                               value="{{ decrypt($initiator->password )}}" readonly
                                               style="max-width: 300px; display: inline-block;">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="togglePassword()">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Actions</strong></td>
                                    <td>
                                        <a class="btn btn-danger"
                                           href="{{ route('initiator-edit', ['q' => 'upin', 'inid' => encrypt($initiator->id)]) }}">
                                            <i class="fa fa-edit"></i> Update
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-danger text-center">
                                <strong>No Initiator Available.</strong> Please create one.
                            </div>
                        @endif
                    </div>
                </div>

                <a href="{{ url('dashboard') }}" class="btn btn-danger mt-3"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>

    @include('users.deactivate_user')
    @include('users.activate_user')

    <script>
        function togglePassword() {
            let passwordField = document.getElementById('passwordField');
            let toggleIcon = document.querySelector('.btn-danger i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
