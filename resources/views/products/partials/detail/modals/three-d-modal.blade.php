<div class="modal fade baseModal_1" id="ProductViewModal" tabindex="-1" aria-labelledby="ProductViewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="ProductViewModalLabel">360˚ view</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="proInfoWrap">
                    <div class="imgWrap">
                        <model-viewer src="" id="3dImage" ar ar-modes="webxr" camera-controls
                            touch-action="pan-y"></model-viewer>
                    </div>
                    <div class="bttmFlx">
                        <div class="item">
                            <div class="btnFlx">
                                <div class="item">
                                    <button type="button" class="baseBtn_1 spaceBtn hoveranim" id="viewArFromModal">
                                        <span class="icon">
                                            <img src="{{ asset('frontend/images/icon-home-modal.svg') }}" width="20"
                                                height="20" alt="icon-notify">
                                        </span>
                                        <span>See in your space</span>
                                    </button>
                                </div>
                                <div class="item">
                                    <button type="button" class="baseBtn_1 hoveranim" id="addFromModal">
                                        <span class="icon">
                                            <img src="{{ asset('frontend/images/icon-cart-modal.svg') }}" width="20"
                                                height="20" alt="icon-cart-modal">
                                        </span>
                                        <span>Add to Cart</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
