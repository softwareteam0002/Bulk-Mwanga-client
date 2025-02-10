
<div class="col-md-12">

    <form action="{{url('users-delegate')}}" method="post">

        {{csrf_field()}}
        <div class="row p-t-20">


            <div class="col-md-12">

                <table class="table">
                    <tbody>
                    <tr style="background-color: #E60100; color: white;">
                        <td>Delegate Role </td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-md-6">

                <label>From</label>
                <select class="form-control" name="from">

                    <option selected disabled>Select User</option>
                    @foreach($users as $user)

                        <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                    @endforeach



                </select>
            </div>
            <div class="col-md-6">

                <label>To</label>

                <select class="form-control" name="to">

                    <option selected disabled>Select User</option>
                    @foreach($users as $user)

                        <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                    @endforeach



                </select>
            </div>

            <div class="col-md-6" style="margin-top: 10px;">

                <div class="form-group">

                    <input placeholder="Start Date" type="text" name="startDate" id="role-name" class="form-control d-input" value="{{old('name')}}">

                </div>

            </div>
            <div class="col-md-6" style="margin-top: 10px;">


                <div class="form-group">

                    <input placeholder="End Date" type="text" name="endDate" id="role-name" class="form-control d-input" value="{{old('name')}}">

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
