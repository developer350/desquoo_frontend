{{-- customization Modal --}}

<!-- Modal -->
<div class="modal customizeModal" id="customizeModal" tabindex="-1" aria-labelledby="customizeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- modal header  --}}
            <div class="hedflex">
                <div class="leftBx">
                    <div class="dflex">
                        <div class="item">
                            <button class="backBtn close" data-bs-dismiss="modal" aria-label="Close">
                                <svg viewBox="0 0 25 24">
                                    <path d="M19.7197 12H5.71973M5.71973 12L12.7197 19M5.71973 12L12.7197 5"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div class="item">
                            <a href="{{ route('home') }}" aria-label="logo" class="logo">
                                <img src="{{ asset('frontend/images/Logo.png') }}" width="112" height="25"
                                    alt="Desqoo">
                            </a>
                        </div>
                        <div class="item ">
                            <a href="javascript:void(0)" class="questLink" data-bs-toggle="modal" data-bs-target="#questionModal" aria-label="questionLink">Got a
                                Question?</a>
                        </div>
                    </div>
                </div>
                <div class="rightBx">
                    <div class="item">
                        <!-- Stepper Header -->
                        <div class="stepper">
                            <div class="step active" data-step="1">Pick a Model</div>
                            <div class="step" data-step="2">Select Colour</div>
                            <div class="step" data-step="3">Addons <span>for your setup</span></div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="gFlx">
                            <div class="txt">Your Total</div>
                            <div class="amt amountChange">₹{{ $product->firstVariant->last_price }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <!-- Content Sections -->
                @if ($isHaveStepOne)
                    <div class="content" id="step1">
                        @include('modals.stepper1')
                    </div>
                @endif
                <div class="content {{ $isHaveStepOne == true ? 'hidden' : '' }}" id="step2">
                    @include('modals.stepper2')
                </div>
                <div class="content hidden" id="step3">
                    @include('modals.stepper3')
                </div>
            </div>

        </div>
    </div>
</div>
