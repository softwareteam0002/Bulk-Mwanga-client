

<!-- Modal -->
<div class="modal fade" id="reject-payment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-activate-deactivates"  role="document">
        <div class="modal-content">



            <div class="modal-header" style="background-color: #E60100; color: white;">
                <h5 class="modal-title" id="exampleModalLongTitle">Batch Payment Rejection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="col-md-12">
                    <input type="hidden" name="organizationId" value="" id="organization-deactivate">

                    <div class="alert-warning" style="height: 50px;">
                        <p style="line-height:50px; margin-left: 2px; text-align: center">
                            Are you sure you want to reject this batch.?
                        </p>
                    </div>
                    <form action="{{url('help/batch-reject')}}" method="post" id="form-reject">

                        {{csrf_field()}}

                        <div class="form-group">

                            <label>Reason</label>
                            <textarea class="form-control" name="reason"></textarea>

                        </div>

                        <input type="hidden" name="batchNo" id="batchNoPayment">
                    </form>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button"  onclick="$('#form-reject').submit();" class="btn btn-danger">Reject</button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>


        </div>
    </div>
</div>
