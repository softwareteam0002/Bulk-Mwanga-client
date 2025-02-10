$(function (e) {

    let url  = 'https://uat.ubx.co.tz:8888/mhb_external-disbursement/get-dashboard-data';

    $.get(url,function (data) {

        let result  =  data.org_dashboard;
        // console.log(result)

        $('#overall').html(result.overall.batches);
        $('#ov-transactions').html(result.overall.transactions);
        $('#o-amount').html('Tsh '+result.overall.amount.toFixed(2));
        $('#pro-batches').html(result.processed.batches);
        $('#pro-transaction').html(result.processed.transactions);
        $('#pro-amount').html('Tsh '+result.processed.amount.toFixed(2));
        $('#succ-batches').html(result.successful.batches);
        $('#succ-transactions').html(result.successful.transactions); //fai-batches
        $('#succ-amount').html('Tsh '+result.successful.amount.toFixed(2));
        $('#fai-batches').html(result.failed.batches);
        $('#fai-transactions').html(result.failed.transactions);
        $('#fai-amount').html('Tsh '+result.failed.amount.toFixed(2));

        $('#recent-batch').html(result.recent_batch.batch_no)
        $('#recent-operation').html(result.recent_batch.operation)
        $('#recent-status').html(result.recent_batch.status_name)
        $('#re-b-pending').html(result.recent_batch.pending)
        $('#re-b-processed').html(result.recent_batch.processed)
        $('#re-b-failed').html(result.recent_batch.failed)

        $('#re-ex-pending').html(result.recent_batch.extended.pending)
        $('#re-ex-processed').html(result.recent_batch.extended.processed)
        $('#re-ex-failed').html(result.recent_batch.extended.failed)


        let color  =  result.recent_batch===4?'bg-danger':'bg-info';
        let progress  = '<div class="progress-bar '+color+'" role="progressbar"' +
        ' style="width: '+result.recent_batch.percentage+'%"' +
        ' aria-valuenow="50" aria-valuemin="2" aria-valuemax="100"' +
        ' data-status="'+result.recent_batch.status_name+'" data-batch-number="'+result.recent_batch.batch_no+'">' +
        '' +result.recent_batch.percentage?result.recent_batch.percentage+'%':''+
            '</div>';

        $('#progress1').html(progress);


        // console.log(result.chart.successful.month)

        let array  =  result.chart.successful;
        const arrayColumn = (array, column) => {
            return array.map(item => item[column]);
        };
        const month = arrayColumn(array, 'month');

        const success  = arrayColumn(array,'amount')

        let arrayFailed  =  result.chart.failed;
        const arrayColumnFailed = (arrayFailed, column) => {
            return arrayFailed.map(item => item[column]);
        };
        const failed = arrayColumnFailed(array, 'amount');

        new Chartist.Line('.ct-chart', {
                labels: month
                , series: [
                    success
                    ,   failed
                ]

            },

            {
                high: result.chart.max_amount,
                low: 0,
                showArea: true,
                fullWidth: false,
                lineSmooth: false,
                plugins: [
                    Chartist.plugins.tooltip()
                ], // As this is axis specific we need to tell Chartist to use whole numbers only on the concerned axis
                axisY: {
                    showGrid: true,
                    onlyInteger: true,
                    offset: 40,
                    labelInterpolationFnc: function (value) {
                        return (value / 1000) + 'k';
                    }
                },
                axisX: {
                    showGrid: true,
                }

            });


    })
})

window.onload = function(){
    function refreshStatus(e) {
        let batch = $(e).data('batch-number');

        $.ajax({
            url: "https://uat.ubx.co.tz:8888/mhb_external-disbursement/"+batch,
            type: "GET",
            async: true,
            success: function(data){
                if(data.response === 'success'){
                    if (data.status === 2 ||
                        data.status === 1 ){
                        $(e).css('width',data.percentage+'%');
                        $(e).css('font-weight','bold');
                        $(e).html(data.percentage+'%');
                        if(data.percentage<10) {
                            $(e).css('color','#333333');
                        }else{
                            $(e).css('color','#FFFFFF');
                        }
                    }else if (data.status === 4 ||
                        data.status === 3 ){
                        $(e).data('data-status',data.status);
                        $(e).css('width',(data.percentage)+'%');
                        $(e).html((data.percentage)+'%');
                        //location.reload();
                    }
                }else{
                    console.log("Failed")
                }
            },
            contentType: false,
            processData: false
        });
    }

    setInterval(function(){

        $("[data-batch-number][data-status='0'], [data-batch-number][data-status='1']").each(function($i,e){
            refreshStatus(e);
        });
    },5000)

}

var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'Tsh',
    minimumFractionDigits: 0
});

$("form").submit(function(event) {
    $('#uploadBtn').prop('disabled', true);
});

window.onload = function () {
    $("[name='batchNo']").hide()
    $("[name='description']").hide()
    $("#payment-schedule-row").hide()
    $("#scheduled-at").hide()
    $("#uploadType").on('change',function () {
        if($('#uploadType option:selected').val()==1){
            $("[name='batchNo']").show()
            $("[name='description']").hide()
            $("#payment-schedule-row").hide()
        }else{
            $("[name='batchNo']").hide()
            $("[name='description']").show()
            $("#payment-schedule-row").show()
        }
    })

    $("#payment-time").on('change',function () {
        if($('#payment-time option:selected').val()==2){
            $("#scheduled-at").show()
        }else{
            $("#scheduled-at").hide()
        }
    })

    //date picker
    let options={
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        minDate: 0,
        maxDate:30
    };
    $("[name='scheduled_at']").datepicker(options);

    //Balance check
    var limitCounter = 0;
    var modalHidden = true;
    $('#btn-check-balance').on('click',function () {

        $('#balance-model #spinner').show()
        $('#balance-model #balance').hide()
        $('#balance-model #balance').html("Checking balance!")
        $('#balance-model').modal('show');
        modalHidden = false;
        limitCounter = 0;

        $.ajax({
            method: "POST",
            url: "https://uat.ubx.co.tz:8888/mhb_external-disbursement/disbursement/query-balance",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function( result ) {
            if(result.status === 'success'){
                checkBalanceAvailability(result.tx_id);
            }else{
                $('#balance-model #spinner').hide()
                $('#balance-model #balance').show()
                $('#balance-model #balance').html("Response:"+result.message)
            }
        }).fail(function (e) {
            $('#balance-model #spinner').hide()
            $('#balance-model #balance').show()
            $('#balance-model #balance').html("Failed to query balance!")
        });
    });

    $('#header-balance-check').on('click',function () {

        $('#balance-model #spinner').show()
        $('#balance-model #balance').hide()
        $('#balance-model #balance').html("Checking balance!")
        $('#balance-model').modal('show');
        modalHidden = false;
        limitCounter = 0;

        $.ajax({
            method: "POST",
            url: "https://uat.ubx.co.tz:8888/mhb_external-disbursement/disbursement/query-balance",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function( result ) {
            if(result.status === 'success'){
                checkBalanceAvailability(result.tx_id);
            }else{
                $('#balance-model #spinner').hide()
                $('#balance-model #balance').show()
                $('#balance-model #balance').html("Response:"+result.message)
            }
        }).fail(function (e) {
            $('#balance-model #spinner').hide()
            $('#balance-model #balance').show()
            $('#balance-model #balance').html("Failed to query balance!")
        });
    });

    $('#balance-model').on('hidden.bs.modal', function (e) {
        modalHidden = true;
    })

    function checkBalanceAvailability(txId) {
        if(modalHidden){
            console.log("Model hidden");
            return;
        }
        $.ajax({
            method: "GET",
            url: "https://uat.ubx.co.tz:8888/mhb_external-disbursement/disbursement/check-balance-availability/"+txId,
        }).done(function( result ) {
            if(result.status === 'success'){
                var MAX = 8;
                if(result.rq_status === 'success'){
                    $('#balance-model #spinner').hide()
                    $('#balance-model #balance').show()
                    $('#balance-model #balance').html(formatter.format(result.balance.available_balance))
                }else if(result.rq_status === 'pending' && limitCounter<=MAX){
                    limitCounter++
                    setTimeout(checkBalanceAvailability,5000,txId)
                }else if (limitCounter>MAX){
                    $('#balance-model #spinner').hide()
                    $('#balance-model #balance').show()
                    $('#balance-model #balance').html('Failed to query balance : Taking too long!')
                }else{
                    $('#balance-model #spinner').hide()
                    $('#balance-model #balance').show()
                    $('#balance-model #balance').html('Failed to query balance!')
                }
            }else{
                $('#balance-model #spinner').hide()
                $('#balance-model #balance').show()
                $('#balance-model #balance').html('Response: '+result.message)
            }
        });
    }
}

$('#all').hide();

$(".type").on("change", function() {

    var id = $(".type").val();

    if (id == 10 || id == 30) {
        $('#all').hide();
        $('#multiple').show();
    } else if (id == 20 || id == 40) {
        $('#all').show();
        $('#multiple').hide();
        $('.result-multiple').html('');
    }
});


$(document).ready(function () {
    // Handle the modal show event
    $('#verifyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batchNo = button.data('batch-no');
        var modal = $(this);
        var yesButton = document.getElementById("yesVerify");
        modal.find('#batchNo').text(batchNo);
        console.log(batchNo);

        yesButton.addEventListener("click", function() {
            window.location.href =
            "https://uat.ubx.co.tz:8888/mhb_external-disbursement/disbursement/mnp-search-request/"  +
                batchNo;
            $("#verifyModal").modal("hide");
        });

    });



});

$(document).ready(function () {
    // Handle the modal show event
    $('#approveModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batchNo = button.data('batch-no');
        var modal = $(this);
        var yesButton = document.getElementById("yesApprove");
        modal.find('#batch-no-payment').val(batchNo);
        //console.log(batchNo);

    });


});

$(document).ready(function () {
    // Handle the modal show event
    $('#rejectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batchNo = button.data('batch-no');
        var modal = $(this);
        modal.find('#batchNoPayment').val(batchNo);
        //console.log(batchNo);

    });


});

$(document).ready(function () {
    // Handle the modal show event
    $('#verifyBankModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batchNo = button.data('batch-no');
        var modal = $(this);
        var yesButton = document.getElementById("yesBankVerify");
        modal.find('#batchNo').text(batchNo);
        console.log(batchNo);

        yesButton.addEventListener("click", function() {
            window.location.href =
                "https://uat.ubx.co.tz:8888/mhb_external-disbursement/bank-disbursement/bankname-search-request/"  +
                batchNo;
            $("#verifyBankModal").modal("hide");
        });

    });



});

$(document).ready(function () {
    // Handle the modal show event
    $('#approveBankModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batchNo = button.data('batch-no');
        var modal = $(this);
        var yesButton = document.getElementById("yesApprove");
        modal.find('#batch-no-payment').val(batchNo);
        //console.log(batchNo);

    });


});

$(document).ready(function () {
    // Handle the modal show event
    $('#rejectBankModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batchNo = button.data('batch-no');
        var modal = $(this);
        modal.find('#batchNoPayment').val(batchNo);
        //console.log(batchNo);

    });


});

$(document).ready(function () {
    $('#dropdownMenuLinks').on('click', function (e) {
        console.log('here');
        e.preventDefault(); // Prevent the default link behavior

        // Show spinner
        $('#balance-amount').text('Loading...Please Wait...');

        // Simulate AJAX request with POST method
        $.ajax({
            url: "https://uat.ubx.co.tz:8888/mhb_external-disbursement/disbursement/query-balance",
            method: 'POST', // Use POST method
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response);
                // Assuming response contains the balance amount as a number
                var balance = response.balance; // Adjust this based on your API response
                var formattedBalance = 'Tshs. ' + balance.toFixed(2); // Format balance with Tshs

                // Update balance display
                $('#balance-amount').text(formattedBalance);
            },
            error: function () {
                console.error('Error fetching balance');
                // Update balance display with "FAILED"
                $('#balance-amount').text('Failed');
            }
        });
    });
});



