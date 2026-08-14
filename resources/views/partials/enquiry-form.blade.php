<div class="enquiryFormWrap">
    <form action="{{ route('enquiry-form') }}" method="post" id="enquiryForm">
        @csrf
        @honeypot
        <input type="hidden" name="model" value="{{ isset($model) ? $model : 'OfficeEnquiry' }}">
        <div class="row">
            <div class="col-12">
                <div class="form-group jqv-group">
                    <label class="visually-hidden">Your Name*</label>
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required
                        data-rule-validName="true" data-msg-required="Please enter a valid name" data-rule-minlength="2"
                        data-rule-maxlength="191">
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group jqv-group">
                    <label class="visually-hidden">Your Phone*</label>
                    <div class="inputWrap hasIcon">
                        <input type="text" name="phone_number" id="phone_number" class="form-control"
                            placeholder="Your Phone" required data-rule-validPhoneNumber="true"
                            data-msg-required="Please enter a valid phone number">
                        <div class="icon">
                            <img src="{{ asset('frontend/images/icon-form-call.svg') }}" width="14" height="14"
                                loading="lazy" alt="icon-form-call">
                        </div>
                    </div>
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group jqv-group">
                    <label class="visually-hidden">Work Email*</label>
                    <div class="inputWrap hasIcon">
                        <input type="email" name="email" class="form-control" placeholder="Work Email" required
                            data-rule-maxlength="191" data-rule-emailOnly="true"
                            data-msg-required="Please enter a valid email">
                        <div class="icon">
                            <img src="{{ asset('frontend/images/icon-form-mail.svg') }}" width="14" height="14"
                                loading="lazy" alt="icon-form-mail">
                        </div>
                    </div>
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="btnWrap">
                    <button type="submit" class="baseBtn_1 hoveranim" aria-label="submit">
                        <span>Submit</span>
                    </button>
                </div>
            </div>
            @if (app('siteSettings')->phone_number != null)
                <div class="col-12">
                    <div class="txt">Talk to us at <a
                            href="tel:{{ app('siteSettings')->formatted_phone_number }}">{{ app('siteSettings')->phone_number }}</a>
                    </div>
                </div>
            @endif
        </div>
    </form>
</div>
