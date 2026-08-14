<form action="{{ route('visit-form') }}" method="POST" id="visitForm">
    @csrf
    @honeypot
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Your Name*</label>
                <div class="inputWrap hasIcon">
                    <div class="icon">
                        <img src="{{ asset('frontend/images/icon-form-name.svg') }}" width="14" height="14"
                            loading="lazy" alt="icon-form-name">
                    </div>
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required
                        data-rule-validName="true" data-msg-required="Please enter a valid name" data-rule-minlength="2"
                        data-rule-maxlength="191">
                </div>
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Pincode*</label>
                <div class="inputWrap hasIcon">
                    <div class="icon">
                        <img src="{{ asset('frontend/images/icon-form-pincode.svg') }}" width="14" height="14"
                            loading="lazy" alt="icon-form-pincode">
                    </div>
                    <input type="text" name="pincode" class="form-control" placeholder="Pincode" required minlength="2"
                        maxlength="10" data-msg-required="Please enter your pincode">
                </div>
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Your Phone Number*</label>
                <input type="hidden" class="country_code" name="country_code" id="country_code">
                <input type="text" name="phone_number" placeholder="55 327 9516" required
                    data-rule-validPhoneNumber="true" data-msg-required="Please enter a valid phone number"
                    class="form-control phone_number" id="phone_number">
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Email*</label>
                <div class="inputWrap hasIcon">
                    <div class="icon">
                        <img src="{{ asset('frontend/images/icon-form-mail.svg') }}" width="14" height="14"
                            loading="lazy" alt="icon-form-mail">
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email" required
                        data-rule-maxlength="191" data-rule-emailOnly="true"
                        data-msg-required="Please enter a valid email">
                </div>
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="btnWrap">
                <button type="submit" class="baseBtn_1 hoveranim" aria-label="submit">
                    <span>Submit Details</span>
                </button>
            </div>
        </div>
    </div>
</form>
