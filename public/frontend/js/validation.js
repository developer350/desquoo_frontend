$(function () {
    $.validator.addMethod(
        "validName",
        function (value, element) {
            // Return false if value is undefined or null
            if (!value) return false;

            // Trim the input value
            value = value.trim();

            // Check for minimum length after trimming (more than 1 character)
            if (value.length <= 1) return false;

            // Check if input consists of only spaces
            if (/^\s*$/.test(value)) return false;

            // Check for double spaces
            if (value.includes("  ")) {
                $(element).val(value.replace("  ", " "));
            }

            // Unicode pattern that allows letters, spaces, hyphens, and apostrophes
            // Includes support for various scripts (Latin, Cyrillic, Greek, Arabic, Chinese, etc.)
            const unicodePattern =
                /^[\p{L}\p{M}'\-][\p{L}\p{M}'\- ]*[\p{L}\p{M}'\-]$/u;

            // Test the value against our pattern
            return unicodePattern.test(value);
        },
        "Please enter a valid name"
    );

    $.validator.addMethod(
        "alphanumericBasicPunctuation",
        function (value, element) {
            // Allows letters, numbers, spaces, and basic punctuation: . , - _ ' " ! ? ( ) &
            return (
                this.optional(element) ||
                /^[a-zA-Z0-9\s.,-_'"!?\(\)&]*$/.test(value)
            );
        },
        "Please enter only alphanumeric characters and basic punctuation marks."
    );

    $.validator.addMethod(
        "alphanumeric",
        function (value, element) {
            // Allows letters, numbers, spaces only
            return this.optional(element) || /^[a-zA-Z0-9\s]*$/.test(value);
        },
        "Please enter only alphanumeric characters."
    );

    var t = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$/i;
    $.validator.addMethod(
        "emailOnly",
        function (e, a) {
            return this.optional(a) || t.test(e);
        },
        "Please enter a valid email."
    );

    $.validator.addMethod(
        "validPhoneNumber",
        function (value, element) {
            // Reject if more than one '+' or if '+' is not at the start
            const plusCount = (value.match(/\+/g) || []).length;
            if (plusCount > 1 || (plusCount === 1 && value[0] !== "+")) {
                return false;
            }

            // Remove non-digit characters for digit-only checks
            var digitsOnly = value.replace(/[^0-9]/g, "");

            // Reject if all digits are zeros
            if (/^0+$/.test(digitsOnly)) {
                return false;
            }

            // Basic format validation - allows numbers and formatting characters
            var basicFormatValid =
                /^[-+() .0-9]+$/.test(value) &&
                digitsOnly.length >= 5 &&
                digitsOnly.length <= 15;

            // If intl-tel-input is available, use it for additional validation
            if (window.intlTelInput) {
                var intlElement = intlTelInput?.getInstance(element);

                if (intlElement) {
                    return basicFormatValid && intlElement.isValidNumber();
                }
            }

            // Fallback to basic validation if intl-tel-input is not available
            return basicFormatValid;
        },
        "Please enter a valid phone number"
    );

    var a = /^\+?[0-9]{1,4}[-\s]?[0-9]{6,14}$/;
    $.validator.addMethod(
        "phoneNumbersOnly",
        function (e, t) {
            return this.optional(t) || a.test(e);
        },
        "Please enter a valid number."
    );
    var n =
        /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:\'[^\']*\')|[^>\s]+))?)*)\s*(\/?)>/;
    $.validator.addMethod(
        "notHTML",
        function (e, t) {
            return this.optional(t) || !n.test(e);
        },
        "Please remove HTML tags."
    );
    $.validator.addMethod(
        "validDate",
        function (e, t) {
            return (
                this.optional(t) ||
                /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/.test(e)
            );
        },
        "Please enter a valid date in DD-MM-YYYY format."
    );
    $.validator.addMethod(
        "validDobDate",
        function (e, t) {
            return (
                this.optional(t) ||
                /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/.test(e) ||
                /^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{2}, \d{4}$/.test(
                    e
                )
            );
        },
        "Please enter a valid date in DD-MM-YYYY or MMM DD, YYYY format."
    );
    var r = /^[a-zA-Z0-9, \-()\/]+$/;
    $.validator.addMethod(
        "validText",
        function (e, t) {
            return this.optional(t) || r.test(e);
        },
        "Please enter a valid input. Only letters, numbers, commas, hyphens, and spaces are allowed."
    );
    $.validator.addMethod(
        "dropdownRequired",
        function (e, t) {
            return (
                "" !== e && !0 !== $(t).find("option:selected").prop("disabled")
            );
        },
        "Please select an option."
    );
    $.validator.addMethod(
        "imageFile",
        function (e, t) {
            var a = t.files[0],
                n = a ? a.type.split("/")[1].toLowerCase() : null;
            if (a && -1 === ["jpg", "jpeg", "png"].indexOf(n)) return !1;
            var r = a ? a.size / 1024 / 1024 : 0;
            return !(a && r > 2);
        },
        "Only JPG, JPEG, and PNG files are allowed, and the file size must be less than 2MB."
    );
    $.validator.addMethod(
        "intelPhoneNumbersOnly",
        function (e, t) {
            var a,
                n =
                    ((
                        $(t)
                            .closest(".form-group")
                            .find('[name="country_code"]')
                            .val() || ""
                    ).trim() || "") + e.replace(/\s+/g, "");
            return (
                this.optional(t) ||
                /^\+?[1-9]\d{0,3}\(?\d{1,4}\)?\d{4,14}$/.test(n)
            );
        },
        "Please enter a valid international phone number."
    );
    $.validator.addMethod(
        "strongPassword",
        (e) => /^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(e),
        "Password must be 8 characters with at least 1 letter and 1 number."
    );
    $.validator.addMethod(
        "passwordMatch",
        function (value, element) {
            const passwordFieldSelector = $(element).data("match");
            const password = $(passwordFieldSelector).val();
            return password === value;
        },
        "Passwords do not match."
    );

    $.validator.addMethod(
        "validMessage",
        function (e, t) {
            return (
                !e ||
                ((e = e
                    .replace(/[ ]{2,}/g, " ") // Only remove multiple spaces (2 or more) and replace with single space
                    .trim()),
                $(t).val(e),
                // Reject multiple tabs and multiple newlines
                !/\t{2,}/.test(e) &&
                    !/\n{3,}/.test(e) &&
                    !/^[^a-zA-Z0-9\s]+$/.test(e) &&
                    ![
                        /'\s*(?:or|and|union|select|insert|update|delete|drop|alter|create|rename)/i,
                        /--[^\n]*$/,
                        /;\s*$/,
                        /<script/i,
                        /javascript:/i,
                        /on\w+\s*=/i,
                        /\{\{.*\}\}/,
                        /\${.*}/,
                        /<\%.*%>/,
                        /constructor\.constructor\s*\(/i,
                        /Function\s*\(/i,
                        /new\s+Function\s*\(/i,
                        /\(\s*select\s*\(/i,
                        /\(\s*alert\s*\(/i,
                        /\(\s*eval\s*\(/i,
                    ].some((t) => t.test(e)))
            );
        },
        "Please enter valid message content."
    );
    $.validator.addMethod(
        "passwordStrength",
        function (value, element) {
            // Check if value meets all requirements
            return (
                this.optional(element) ||
                (value.length >= 8 && // Minimum 8 characters
                    /\d/.test(value) && // Contains at least 1 number
                    /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) // Contains at least 1 special character
            );
        },
        "Password must be at least 8 characters long and contain 1 number and 1 special character"
    );
});
