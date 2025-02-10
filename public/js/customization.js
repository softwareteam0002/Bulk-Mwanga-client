$(function (event) {
    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(".d-input").attr("autocomplete", "off");

    var date_input = $('input[name="startDate"],input[name="endDate"]');

    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    var options = {
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input.datepicker(options);

    $('.div-animate-form').animate({
        width: "100%",

        marginLeft: "4px;",
        // fontSize: "3em",
        borderWidth: "1px"
    }, 400);


    //call select2
    $('.organization-select').select2({

        placeholder: "Select Organization",
        allowClear: true,

    });

    $('.dashboard-box-animate').animate({
        width: "100%",

        marginLeft: "40px;",
        // fontSize: "3em",
        borderWidth: "1px"
    }, 4000);


    $('.role-delete').click(function () {

        let roleId = $(this).attr('id');

        $('#roleId-Delete').val(roleId);

        $('#delete-role-modal').modal('show');

    });


    $(document).on('click', '.user-deactivate', function () {
        console.log('reached');
        let userId = $(this).attr('id');
        console.log(userId);
        let fullName = $('fullName').val();
        console.log(fullName);
        $('#userId-Delete').val(userId);

        $('#deactivate-user-modal').modal('show');

    });

    $(document).on('click', '.user-activate', function () {

        let userId = $(this).attr('id');

        let fullName = $('fullName').val();

        console.log(userId);

        $('#userIdActivate').val(userId);

        $('#user-activate-modal').modal('show');

    });


    $('.region').change(function () {
        var url = 'https://uat.ubx.co.tz:8888/mhb_internal-disbursement/help/districts/get-all';
        $('.district').children().remove();

        var id = $(this).val();

        var districts = [];

        $.get(url, {id: id}, function (data) {

            console.log(data[0].name);

            for (var i = 0; i < data.length; i++) {

                $(".district").append('<option value=' + data[i].id + '>' + data[i].name + '</option>');
            }

        });


    });


    var constraints = {

        shortCode: {
            presence: true,
            length: {
                minimum: 6,
                message: "must be at least 6 characters"
            }
        }
    };

    DisableOrganization();
    EnableOrganization();
    numberApproval();
    saveApproval();

});

function numberApproval() {

    $(document).on('click', '.organization-number-approval', function (e) {

        let id = $(this).attr('id');

        console.log("id " + id);

        let number = $(this).closest('tr').find('td .numberl').html();

        $('.noApproval').val(number);
        console.log("number  " + number);

        $('.save-approval').attr('id', id);


        $('#no-approval-modal').modal('show');

    });

}

function saveApproval() {
    var urlApproval = 'https://uat.ubx.co.tz:8888/mhb_internal-disbursement/organization/number-approvals';

    var token = $('meta[name="csrf-token"]').attr("content");

    $('.save-approval ').click(function (event) {

        $('.noApproval').removeClass('notValid');

        event.preventDefault();

        let noApproval = $('.noApproval').val();


        if (noApproval.length < 1) {


            $('.noApproval').addClass('notValid');

            return false;

        }

        let id = $(this).attr('id');

        console.log(id);

        $('.loading').show();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: urlApproval,
            method: 'post',
            data: {
                id: id,
                number: noApproval,
                _token: token,

            },

            success: function (data) {

                $('.loading').hide();

                console.log(data.message);
                if (data.status === 1) {

                    $('.approval-result').html(data.message);

                    setTimeout(explode, 2000);
                    location.reload();


                } else {

                    $('.approval-result').html(data.message);
                    setTimeout(explode, 2000);

                }

            },
            error: function (data) {

                $('.loading').hide();


            }
        });


    });

}

function explode() {

    $('.approval-result').fadeOut();

}

function DisableOrganization() {

    $(document).on('click', '.disable-organization', function (e) {

        let id = $(this).attr('id');

        $('#organization-deactivate').val(id);
        console.log('id for disable agent account ' + id);
        $('#organization-deactivate-modal').modal('show');

    });

}

function EnableOrganization() {


    // $('.enable-organization').click(function (event) {
    $(document).on('click', '.enable-organization', function (e) {
        let id = $(this).attr('id');

        $('#organization-activate').val(id);
        console.log('id for disable agent account ' + id);
        $('#organization-activate-modal').modal('show');

    });
}


var setConfirmationModal = function (btn, callback, title, body) {
    $('#mi-modal .modal-title').html(title);
    $('#mi-modal .modal-body').html(body);
    btn.on('click', function (event) {
        $('#mi-modal').modal('show');
        event.preventDefault();
    });

    $('#modal-btn-yes').on('click', function () {
        callback(true, btn);
        $('#mi-modal').modal('hide');
    });

    $('#modal-btn-no').on('click', function () {
        callback(false, btn);
        $('#mi-modal').modal('hide');
    });
};

