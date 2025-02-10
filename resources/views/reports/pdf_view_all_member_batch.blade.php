    <!doctype html>
    <html lang="en">

    <head>

        <title>pdf</title></head>

    <body>

    <table class="table table-striped  table-bordered" id="table">

        <thead>

        <tr>

            <th>No</th>

            <th>Full Name</th>
            <th>Status</th>
            <th>Uploaded Date</th>

        </tr>
        </thead>

        <tbody>

            @foreach($disbursementsPayments as $index=>$data)
                <tr>

                    <td>{{$index+1}}</td>

                    <td>{{$data->first_name.' '.$data->last_name}}</td>
                    <td>

                        @if($data->batch_status_id==0)
                            Pending
                        @elseif($data->batch_status_id==1)
                            Mnp Search Complete
                        @elseif($data->batch_status_id==2)
                            Payment Complete
                        @endif
                    </td>

                    <td>{{$data->created_at}}</td>



                </tr>

            @endforeach

        </tbody>
    </table>

    </body>
    </html>
