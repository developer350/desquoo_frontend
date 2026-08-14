<form action="{{ route('question-form') }}" method="POST" id="question-form">
    @csrf
    @honeypot
    <input type="hidden" name="product_id" value="{{ $cms->product_id }}">
    <div class="row">
        <div class="col-12">
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
        <div class="col-12">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Your Phone*</label>
                <input type="hidden" class="question_country_code" name="country_code">
                <input type="text" name="phone_number" placeholder="Your Phone" required
                    data-rule-validPhoneNumber="true" data-msg-required="Please enter a valid phone number"
                    class="form-control question_phone_number" maxlength="25">
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Work Email*</label>
                <div class="inputWrap hasIcon">
                    <div class="icon">
                        <img src="{{ asset('frontend/images/icon-form-mail.svg') }}" width="14" height="14"
                            loading="lazy" alt="icon-form-mail">
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Work Email" required
                        data-rule-maxlength="191" data-rule-emailOnly="true"
                        data-msg-required="Please enter a valid email">
                </div>
                <div class="help-block danger"></div>
            </div>
        </div>
    </div>
</form>
