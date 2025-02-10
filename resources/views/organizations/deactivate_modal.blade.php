

<!-- Modal -->
<div class="modal fade" id="organization-deactivate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-activate-deactivate"  role="document">
        <div class="modal-content">

            <form  method="post" action="{{url('/organization/deactivate')}}">

                {{csrf_field()}}
            <div class="modal-header" style="background-color: #E60100; color: white;">
                <h5 class="modal-title" id="exampleModalLongTitle"> Deactivate Organization</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>

            <div class="modal-body">



                    <div class="col-md-12">

                        <input type="hidden" name="organizationId" value="" id="organization-deactivate">

                        <div class="alert-warning" style="height: 50px;">
                            <p style="line-height:50px; margin-left: 2px; text-align: center">
                                Are you sure you want to deactivate ?
                            </p>
                        </div>


                    </div>

                    <div class="col-md-12" style="margin-top: 10px;">

                        <div class="form-group">

                        </div>

                    </div>



            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Deactivate</button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>
            </form>

        </div>
    </div>
</div>
