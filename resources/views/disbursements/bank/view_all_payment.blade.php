
@extends('layouts.master')

@section('content')

    <div class="container" style="background-color: white; margin-top: 20px">

       <div class="row">

           <div class="col-md-12">

               @include('partials.flash_error')

           </div>
            <div class="col-md-12" style="margin-bottom: 10px;">

{{--                 <p>Uploaded Files</p>--}}
                {{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('disbursement-progress')}}

            </div>

        </div>

        <div class="row">

          <div class="col-md-12">

              <table class="table table-bordered">
                  <tbody>
                  <tr>
                      <th>Batch Number</th><td style="color: #E60100">{{$batch_no}}</td>
                  </tr>
                  </tbody>
              </table>


              <a href="{{route('download.batch-payment',[encrypt($data->batch_no)])}}" class="btn btn-danger fa fa-download">
                  Download This Batch
              </a>

              <table class="table table-striped table-bordered" id="table">

                  <thead>

                  <tr>

                      <th>No</th>

                      <th>Full Name</th>
                      <th>Phone number</th>
                      <th>Network</th>
                      <th>Status</th>
                      <th>Uploaded Date</th>

                  </tr>
                  </thead>

                  <tbody>

                  @foreach($disbursements as $index=>$data)
                  <tr>

                      <td>{{$index+1}}</td>

                      <td>{{$data->first_name.' '.$data->last_name}}</td>
                      <td>{{$data->phone_number}}</td>
                      <td>{{$data->network_name}}</td>
                      <td>

                              @if($data->payment_status==0)
                              Pending
                              @elseif($data->payment_status==1)
                              Paid
                              @elseif($data->payment_status==2)
                              Failed
                              @endif
                      </td>

                      <td>{{$data->created_at}}</td>



                  </tr>

                      @endforeach

                  </tbody>
              </table>

          </div>
        </div>

    </div>

@endsection
