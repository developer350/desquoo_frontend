@push('css')
    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
@endpush
<div>
    @if ($supportSectionCms != null && $supportSectionCms->status == 1)
        <section id="ProductExpertSection">
            <div class="container">
                <div class="proExpertBx">
                    <div class="lftSd">
                        <div class="cntWrap">
                            <div class="tle">{!! $supportSectionCms->title !!}</div>
                            <div class="txt">{{ $supportSectionCms->description }}</div>
                            <div class="btnWrap">
                                @if ($supportSectionCms->visit_store_btn_show)
                                    <div>
                                        <button class="baseBtn_2 hoveranim" data-bs-toggle="modal"
                                            data-bs-target="#VisitStoreModal">
                                            <span>{{ $supportSectionCms->visit_store_btn_text }}</span>
                                        </button>
                                    </div>
                                @endif
                                @if ($supportSectionCms->get_a_virtual_demo && $supportSectionCms->calendly_meeting_link != null)
                                    <div>
                                        <a href="javascript:void(0);"
                                            onclick="Calendly.initPopupWidget({url: '{{ $supportSectionCms->calendly_meeting_link }}'});return false;"
                                            class="baseBtn_2 hoveranim">
                                            <span>{{ $supportSectionCms->get_a_virtual_demo_btn_text }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="rgtSd">
                        <div class="imgWrap">
                            <img src="{{ $supportSectionCms->image_value }}" width="100" height="100"
                                loading="lazy" alt="{{ $supportSectionCms->image_alt_text }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- VISIT_STORE_MODAL -->
        <div class="modal fade baseModal_1" id="VisitStoreModal" tabindex="-1" aria-labelledby="VisitStoreModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title" id="VisitStoreModalLabel">Visit Our Store</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="addressWrap">
                            @if ($siteSettings->map_iframe != null)
                                <div class="locWrap">
                                    {!! $siteSettings->map_iframe !!}
                                </div>
                            @endif
                            <div class="locInfoWrap">
                                <div class="item">
                                    <div class="locInfoBx">
                                        <div class="tle">Address</div>
                                        <div class="txt">
                                            {!! $siteSettings->address !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="locInfoBx">
                                        <div class="tle">Contact</div>
                                        @if ($siteSettings->phone_number != null)
                                            <div class="txt">
                                                <a
                                                    href="tel:{{ $siteSettings->formatted_phone_number }}">{{ $siteSettings->phone_number }}</a>
                                            </div>
                                        @endif
                                        @if ($siteSettings->email != null)
                                            <div class="txt">
                                                <a
                                                    href="mailto:{{ $siteSettings->email }}">{{ $siteSettings->email }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="formWrap">
                                <div class="formWrapTle">
                                    <div class="tle">{{ $supportSectionCms->form_title }}</div>
                                    <div class="txt">{{ $supportSectionCms->form_description }}</div>
                                </div>

                                @include('partials.visit-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('js')
    <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>
@endpush
