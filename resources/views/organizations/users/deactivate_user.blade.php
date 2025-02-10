<!-- Modal -->
<div class="modal fade" id="deactivate-user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deactivate User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <form action="{{url('users/deactivate')}}" method="post">

                {{csrf_field()}}

                <div class="modal-body">

                    <h3>Are you sure you want to delete this User?</h3>
                    <input type="hidden" name="userId" id="userId-Delete">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Deactivate</button>
                </div>
            </form>
        </div>
    </div>
</div>
