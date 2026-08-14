<div class="modal fade baseModal_1" id="addressEditModal" tabindex="-1" aria-labelledby="addressEditModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="addressEditModalLabel">Edit Address</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressEditForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    @include('partials.edit-address-form')
                </div>
                <div class="modal-footer">
                    <div class="item">
                        <button type="button" class="cncl" data-bs-dismiss="modal" aria-label="cancel">
                            <span>Cancel</span>
                        </button>
                    </div>
                    <div class="item">
                        <button type="submit" class="baseBtn_1 hoveranim" aria-label="submit">
                            <span>Submit</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal removeModal" id="removeModalAddress" tabindex="-1" aria-labelledby="removeModalAddressLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="removeBx">
                <div class="mainTxt">Delete this address?</div>
                <div class="flxbx">
                    <div class="item">
                        <a href="javascript:void(0)" class="rmBtns deleteAddress" aria-label="remove_button">
                            <span>Remove</span>
                        </a>
                    </div>
                    <div class="item">
                        <button type="button" data-bs-dismiss="modal" aria-label="remove_button" class="rmBtns">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
