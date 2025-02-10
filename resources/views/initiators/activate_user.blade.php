<!-- Modal -->
<div class="modal fade" id="user-activate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-activate-deactivate" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Activate User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{url('users/activate')}}" method="post">

                {{csrf_field()}}

                <div class="modal-body">

                    <h4>Are You Sure You Want To Activate This User?</h4>
                    <input type="hidden" name="userId" id="userIdActivate">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Activate</button>
                </div>
            </form>
        </div>
    </div>
</div>
