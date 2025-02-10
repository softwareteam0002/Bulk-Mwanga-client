

<!-- Modal -->
<div class="modal fade" id="rejection-reason-payment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-activate-deactivates"  role="document">
        <div class="modal-content">



            <div class="modal-header" style="background-color: #E60100; color: white;">
                <h5 class="modal-title" id="exampleModalLongTitle">Batch Payment Rejection Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="col-md-12">
                    <input type="hidden" name="organizationId" value="" id="organization-deactivate">

                    <div class="alert-warning" style="height: 50px;">
                        <p style=" margin-left: 2px; text-align: center">

                            {{$batch->reason_if_rejected}}

                        </p>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
{{--                <button type="button"  onclick="$('#form-reject').submit();" class="btn btn-danger">Reject</button>--}}

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>


        </div>
    </div>
</div>
