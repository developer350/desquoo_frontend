<form class="FormWrap" action="{{ route('addresses.store') }}" method="POST" id="addressForm">
    @csrf
    <div class="FormBx">
        <div class="row">
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="name">Name*</label>
                    <input type="text" name="name" id="name" placeholder="Name" class="form-control" required
                        data-rule-validName="true" minlength="2" maxlength="191">
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="email">Email*</label>
                    <input type="email" name="email" placeholder="Email" class="form-control" required
                        data-rule-emailOnly="true" maxlength="191">
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="phone_number">Phone*</label>
                    <div class="mblCode">
                        <input type="hidden" class="country_code" name="country_code">
                        <input type="text" name="phone_number" id="phone_number" placeholder="Your Phone" required
                            data-rule-validPhoneNumber="true" data-msg-required="Please enter a valid phone number"
                            class="form-control phone_number">
                    </div>
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="address_line_1">Address Line 1*</label>
                    <input type="text" name="address_line_1" id="address_line_1" placeholder="Address Line 1"
                        class="form-control" required data-rule-noHtml="true" minlength="3" maxlength="500">
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="address_line_2">Address Line 2</label>
                    <input type="text" name="address_line_2" id="address_line_2" placeholder="Address Line 2"
                        class="form-control" data-rule-noHtml="true" minlength="3" maxlength="500">
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="city">City*</label>
                    <select name="city" id="city" class="form-control" required data-placeholder="Select City">
                        <option value=""></option>
                    </select>
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="state">State*</label>
                    <select name="state" id="state" class="form-control" required data-placeholder="Select State">
                        <option value=""></option>
                        <option value="Kerala" selected>Kerala</option>
                    </select>
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="postal_code">Postal Code*</label>
                    <input type="number" name="postal_code" id="postal_code" placeholder="Postal Code"
                        class="form-control" required data-rule-noHtml="true" minlength="3" maxlength="20">
                    <div class="help-block danger"></div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6">
                <div class="form-group jqv-group">
                    <label for="landmark">Landmark</label>
                    <input type="text" name="landmark" id="landmark" placeholder="Landmark"
                        class="form-control" data-rule-noHtml="true" minlength="3" maxlength="191">
                    <div class="help-block danger"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="btnWrap">
        <div class="item">
            <a href="javascript:void(0)" class="cmnBtn cncl hoveranim" aria-label="cancelBtn">
                <span>CANCEL</span>
            </a>
        </div>
        <div class="item">
            <button type="submit" class="hoveranim save cmnBtn">
                <span>SAVE</span>
            </button>
        </div>
    </div>
</form>
