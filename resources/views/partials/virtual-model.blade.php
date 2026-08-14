<!-- VIRTUAL_MODAL -->
<div class="modal fade baseModal_1" id="VirtualModal" tabindex="-1" aria-labelledby="VirtualModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="VirtualModalLabel">Book a virtual Demo</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="virtualDemoWrap">
                    <div class="lftSd">
                        <div class="virtualDemoTle">
                            <div class="imgWrap">
                                <img src="{{ asset('frontend/images/virtualdemo-logo-1.svg') }}" alt="virtualdemo-logo"
                                    width="126" height="27" loading="lazy">
                            </div>
                            <div class="tle">Virtual Demo</div>
                        </div>
                        <div class="virtuaInfoFlx">
                            <div class="item">
                                <div class="virtualInfoBx">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/images/virtualdemo-time.svg') }}"
                                            alt="virtualdemo-time" width="20" height="20" loading="lazy">
                                    </div>
                                    <div class="txt">30 min</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="virtualInfoBx">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/images/virtualdemo-video.svg') }}"
                                            alt="virtualdemo-video" width="20" height="20" loading="lazy">
                                    </div>
                                    <div class="txt">Web conferencing details provided upon confirmation.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rgtSd">
                        <div class="calendarWrap">
                            <div class="tle">Select a Date & Time</div>
                            <div class="formWrap">
                                @include('partials.book-calendar')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
