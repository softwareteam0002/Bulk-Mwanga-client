<div class="row row-box org-rowbox">
    @if(!empty($initiatorAlert))
        <div class="col-md-12 alert alert-warning text-center">
            <strong>{{$initiatorAlert}}</strong>
        </div>
    @endif
    <div class="col-md-12 text-center">
        <h4>{{strtoupper($organizationName->name)}}</h4>
    </div>
</div>

<div class="row row-box org-cardrow mt-3">
    @include('partials.flash_error')

    @foreach(['overall' => 'Overall', 'processed' => 'Processed', 'successful' => 'Successful', 'failed' => 'Failed'] as $key => $title)
        <div class="col-md-3">
            <div class="card shadow-sm border-radius">
                <div class="card-body text-center">
                    <h4 class="text-center"> <i class="fa fa-list-alt"></i> {{ strtoupper($title) }}</h4>
                    <hr/>

                    <div class="row align-items-center">
                        <div class="col-5 card-detail">
                            <span>Batches</span>
                            <div>
                                <b id="{{ $key }}-batches">{{ number_format($org_dashboard[$key]['batches']) }}</b>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <div class="vertical-separator"></div>
                        </div>
                        <div class="col-5 card-detail p-0">
                            <span>Transactions</span>
                            <div>
                                <b id="{{ $key }}-transactions">{{ number_format($org_dashboard[$key]['transactions']) }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="card-detail mt-1">
                        <span>Total Amount</span>
                        <div>
                            <b id="{{ $key }}-amount">Tsh {{ number_format($org_dashboard[$key]['amount']) }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row row-box org-cardrow">
    <div class="col-md-6">
        <div class="card shadow-sm border-radius">
            <div class="card-body text-center">
                <h4 class="text-center"><i class="fa fa-line-chart" aria-hidden="true"></i> Transactions Volume (Tsh)</h4>
                <hr/>
                <div class="ct-chart ctchart-height"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-radius" id="recent-batch-div">
            <div class="card-body text-center">
                <h4 class="text-center"><i class="fa fa-spinner" aria-hidden="true"></i> Latest Batch</h4>
                <hr/>
                <table class="table-sm table-responsive boxtable">
                    <tr>
                        <td>Batch Number:</td>
                        <td><strong id="recent-batch"></strong></td>
                        <td>Operation:</td>
                        <td><strong id="recent-operation"></strong></td>
                    </tr>

                    <tr>
                        <td><strong id="recent-status"></strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <div class="mt-2">
                    <table class="table table-sm table-bordered tbl-preprofa">
                        <thead>
                        <tr>
                            <th>Pending</th>
                            <th>Processed</th>
                            <th>Failed</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><strong id="re-b-pending"></strong></td>
                            <td><strong id="re-b-processed"></strong></td>
                            <td><strong id="re-b-failed"></strong></td>
                        </tr>
                        <tr>
                            <td><strong id="re-ex-pending"></strong></td>
                            <td><strong id="re-ex-processed"></strong></td>
                            <td><strong id="re-ex-failed"></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="progressbarbox mt-4">
                    <div class="progress" id="progress1"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<link href="{{url('public/css/organization.css')}}" rel="stylesheet">
