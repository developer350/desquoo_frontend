<div class="row">
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_name">Name*</label>
            <input type="text" name="name" id="edit_name" placeholder="Name" class="form-control" required
                data-rule-validName="true" minlength="2" maxlength="191">
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_email">Email*</label>
            <input type="email" name="email" id="edit_email" placeholder="Email" class="form-control" required
                data-rule-emailOnly="true" maxlength="191">
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_phone_number">Phone*</label>
            <div class="mblCode">
                <input type="hidden" class="edit_country_code" name="country_code">
                <input type="text" name="phone_number" id="edit_phone_number" placeholder="Your Phone" required
                    data-rule-validPhoneNumber="true" data-msg-required="Please enter a valid phone number"
                    class="form-control edit_phone_number">
            </div>
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-6 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_address_line_1">Address Line 1*</label>
            <input type="text" name="address_line_1" id="edit_address_line_1" placeholder="Address Line 1"
                class="form-control" required data-rule-noHtml="true" minlength="3" maxlength="500">
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-6 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_address_line_2">Address Line 2</label>
            <input type="text" name="address_line_2" id="edit_address_line_2" placeholder="Address Line 2"
                class="form-control" data-rule-noHtml="true" minlength="3" maxlength="500">
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_city">City*</label>
            <select name="city" id="edit_city" class="form-control" required data-placeholder="Select City">
                <option value=""></option>
            </select>
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_state">State*</label>
            <select name="state" id="edit_state" class="form-control" required data-placeholder="Select State">
                <option value=""></option>
                <option value="Kerala" selected>Kerala</option>
            </select>
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_postal_code">Postal Code*</label>
            <input type="number" name="postal_code" id="edit_postal_code" placeholder="Postal Code" required
                class="form-control" required data-rule-noHtml="true" minlength="3" maxlength="20">
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_landmark">Landmark</label>
            <input type="text" name="landmark" id="edit_landmark" placeholder="Landmark" class="form-control"
                data-rule-noHtml="true" minlength="3" maxlength="191">
            <div class="help-block danger"></div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6">
        <div class="form-group jqv-group">
            <label for="edit_landmark">Gst Number</label>
            <input type="text" name="gstnumber" id="edit_gstnumber" placeholder="Gst Number"
                class="form-control" data-rule-noHtml="true" minlength="3" maxlength="191">
            <div class="help-block danger"></div>
        </div>
    </div>

</div>
