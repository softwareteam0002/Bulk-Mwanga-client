

<!-- Modal -->
<div class="modal fade" id="approve-payment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-activate-deactivate"  role="document">
        <div class="modal-content">

            <div class="modal-header" style="background-color: #E60100; color: white;">
                <h5 class="modal-title" id="exampleModalLongTitle">Approve Batch Payment </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>
            <form id="form-approve" action="{{url('bank-disbursement/payment-request')}}" method="get">

            <div class="modal-body">

                <div class="col-md-12">


                        <input type="hidden" name="organizationId" value="" id="organization-deactivate">

                        {{-- encrypt($data->batch_no)--}}

                        <input type="hidden" name="batch_no" value="" id="batch-no-payment">

                        <div class="alert-warning" style="height: 50px;">

                            <p style="line-height:50px; margin-left: 2px; text-align: center">
                                Are you sure you want to Approve this batch.?
                            </p>

                        </div>
{{--                    </form>--}}
                </div>

            </div>
            <div class="modal-footer">

                <button type="submit"   class="btn btn-danger">Approve</button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>
</form>
        </div>
    </div>
</div>
