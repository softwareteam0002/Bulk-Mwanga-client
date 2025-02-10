
@extends('layouts.master')



@section('content')


    <div class="container" style="background-color: white; margin-top: 20px">

        <div class="row">

            <div class="col-md-12 breadcrumb-margin" >

                {{ Breadcrumbs::render('roles') }}

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


                    <div class="col-md-12">

                        <form action="{{url('setup/general-setup')}}" method="post">

                            {{csrf_field()}}
                            <div class="row p-t-20">

                                <div class="col-md-12">

                                    <table class="table">
                                        <tbody>
                                        <tr style="background-color: #E60100; color: white;">
                                            <td>General Setup </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>



                                <div class="col-md-6" style="margin-top: 10px;">

                                    <div class="form-group">

                                        <ul class="rol-perm-list">

                                                    <li>
                                                        <span class="perm-role-span"><input type="checkbox" name="withdraw" class="checkbox-custom" value="{{$withdrawStatus->withdraw}}"

                                                                                            @if($withdrawStatus->withdraw=='YES')

                                                                                                checked

                                                                                            @endif
                                                            >Allow Withdraw Fee</span>
                                                    </li>


                                        </ul>
                                    </div>

                                </div>


                                <div class="col-md-12">

                                    <div class="form-group">


                                        <button type="submit" class="btn btn-danger">Save</button>

                                    </div>

                                </div>

                            </div>




                        </form>

                    </div>

            </div>
        </div>

    </div>

    @include('roles.delete_role')

@endsection
