<div class="row row-box" style="margin-left: 5px;">

    <div class="col-md-12">

        @include('partials.flash_error')

    </div>

    <div class="col-md-3 box-title">
        <h3><i class="fa fa-list-alt"></i> OVERALL</h3>
        <div class="box-title-left-dashboard">
            <span>Batches</span>
            <div>
                <b style="font-size: 30px;">{{$vodaDashboard['totalBatches']}}</b>
            </div>
        </div>
        <div class="box-separator-dashboard"></div>
        <div class="box-title-left-dashboard">
            <span>Transactions</span>
            <div>
                <b style="font-size: 30px;"></b>
            </div>
        </div>
        <br>
        <br>
        <div>Tsh </div>
    </div>


    <div class="col-md-3 box-title">

        <div class="box-title-left">

            <p>
                Number Of
            </p>
            <p>Organizations</p>

            <p>
                <b style="font-size: 30px;">{{$vodaDashboard['organizations']}}</b>
            </p>
        </div>
        <div class="box-title-right">

            <a class="fa fa-list-alt">

            </a>
        </div>

    </div>
    <div class="col-md-3 box-title">


        <div class="box-title-left">

            <p>
                Number Of
            </p>
            <p>Successful Batches</p>

            <p>
                <b style="font-size: 30px;">{{$vodaDashboard['successBatches']}}</b>
            </p>
        </div>
        <div class="box-title-right">

            <a class="fa fa-list-alt">

            </a>
        </div>

    </div>
    <div class="col-md-3 box-title">


        <div class="box-title-left">

            <p>
                Number Of
            </p>
            <p>Failed Batches</p>

            <p>
                <b style="font-size: 30px;">{{$vodaDashboard['failedBatches']}}</b>
            </p>
        </div>
        <div class="box-title-right">

            <a class="fa fa-list-alt">

            </a>
        </div>

    </div>


</div>

