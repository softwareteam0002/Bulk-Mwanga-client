

<!-- Modal -->
<div class="modal fade" id="no-approval-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-editable"  role="document">
        <div class="modal-content">


                <div class="modal-header" style="background-color: #E60100; color: white;">
                    <h5 class="modal-title" id="exampleModalLongTitle">Approvals <label class="loading"></label> <label class="badge badge-success approval-result"></label></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>

                <div class="modal-body">


                        <div class="row">

                            <div class="col-md-7 pull-left">
                                <input     type="text"   class="form-control noApproval" name="no" value="" id="no">

                            </div>

                            <div class="col-md-5 pull-right" >

                                <button type="button" id="" class="btn btn-danger save-approval  fa fa-check-circle"></button>

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            </div>

                        </div>







                </div>



        </div>
    </div>
</div>

<script>

    var urlApproval  = '{{ url('organization/number-approvals') }}';
    var token = '{{ csrf_token() }}';
</script>
