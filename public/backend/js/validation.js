$(function () {
    // Ensure the value is within the specified min and max range
    $(document).on("input", ".numeric-input", function () {
        var inputValue = $(this).val();
        // Remove non-digit characters
        inputValue = inputValue.replace(/[^\d]/g, "");

        // Get min and max values from attributes or use defaults
        var min =
            $(this).attr("min") !== undefined
                ? parseInt($(this).attr("min"), 10)
                : 0;
        var max =
            $(this).attr("max") !== undefined
                ? parseInt($(this).attr("max"), 10)
                : 2147483647;

        // Ensure the value is between min and max
        var value = Math.max(
            min,
            Math.min(parseInt(inputValue, 10) || min, max)
        );
        $(this).val(value);
    });

    // On blur: clamp value between min/max and format to 2 decimals
    $(document).on("blur", ".decimal-input", function () {
        let val = $(this).val(),
            min = parseFloat($(this).attr("min") || 0),
            max = parseFloat($(this).attr("max") || 99999999999999999.99);

        if (val && val !== ".") {
            $(this).val(
                Math.max(min, Math.min(parseFloat(val), max)).toFixed(2)
            );
        }
    });

    // Allow only numeric input and "+" for phone numbers
    $(".phone-field").on("keypress", function (e) {
        var keyCode = e.which || e.keyCode;
        if ((keyCode < 48 || keyCode > 57) && keyCode !== 43) {
            e.preventDefault();
        }
    });

    // Validation method to allow only letters and spaces
    $.validator.addMethod(
        "lettersOnly",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        },
        function (e, element) {
            return (
                "Please enter a valid " + element.name.replace(/_/g, " ") + "."
            );
        }
    );

    // Validation method for valid email addresses
    $.validator.addMethod(
        "emailOnly",
        function (value, element) {
            return (
                this.optional(element) ||
                /^[a-z0-9._%+-]+@[a-z0-9-]+(\.[a-z]{2,63})+$/i.test(value)
            );
        },
        "Please enter a valid email."
    );

    // Validation method for international phone numbers
    $.validator.addMethod(
        "intelPhoneNumbersOnly",
        function (value, element) {
            var countryCode = (
                $(element)
                    .closest(".form-group")
                    .find('[name="country_code"]')
                    .val() || ""
            ).trim();

            var fullNumber =
                (countryCode ? countryCode : "") + value.replace(/\s+/g, ""); // Remove spaces

            var phoneRegex = /^\+?[1-9]\d{0,3}\(?\d{1,4}\)?\d{4,14}$/; // Adjusted regex (no spaces)

            return this.optional(element) || phoneRegex.test(fullNumber);
        },
        "Please enter a valid international phone number."
    );

    // Validation method for phone numbers with an optional "+"
    $.validator.addMethod(
        "phoneNumbersOnly",
        function (value, element) {
            return (
                this.optional(element) ||
                /^\+?[0-9]{1,4}[-\s]?[0-9]{6,14}$/.test(value)
            );
        },
        "Please enter a valid number."
    );

    // Validation method to disallow HTML tags
    $.validator.addMethod(
        "notHTML",
        function (value, element) {
            return (
                this.optional(element) ||
                !/<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:\'[^\']*\')|[^>\s]+))?)*)\s*(\/?)>/.test(
                    value
                )
            );
        },
        "Please remove HTML tags."
    );

    // Validation method to allow only letters, numbers, commas, hyphens, and spaces
    $.validator.addMethod(
        "validText",
        function (value, element) {
            return (
                this.optional(element) || /^[a-zA-Z0-9, \-()\/]+$/.test(value)
            );
        },
        "Please enter a valid input. Only letters, numbers, commas, hyphens, and spaces are allowed."
    );

    // Generic validation method for regex patterns
    $.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        function (params, element) {
            return "Please enter a valid " + element.name + ".";
        }
    );

    $.validator.addMethod(
        "strongPassword",
        (e) =>
            /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/.test(e),
        "Password must be at least 8 characters with at least 1 letter, 1 number, and 1 special character."
    );

    $.validator.addMethod(
        "lessThanCompare",
        function (value, element, param) {
            if (value === "") return true; // Skip if empty
            var target = $(param);
            if (
                this.settings.onfocusout &&
                target.not(".validate-equalTo-blur").length
            ) {
                target.addClass("validate-equalTo-blur");
                target.on("blur.validate-lessThanEqual", function () {
                    $(element).valid();
                });
            }
            return parseFloat(value) < parseFloat(target.val());
        },
        "Offer price must be less than the price."
    );

    $.validator.addMethod(
        "gtOrEq",
        function (value, element, param) {
            if (value === "") return true; // Skip if empty
            var target = $(param);
            if (
                this.settings.onfocusout &&
                target.not(".validate-gtOrEq-blur").length
            ) {
                target.addClass("validate-gtOrEq-blur");
                target.on("blur.validate-gtOrEq", function () {
                    $(element).valid();
                });
            }
            return parseFloat(value) >= parseFloat(target.val());
        },
        "Max quantity must be greater than or equal to Min quantity."
    );

    $.validator.addMethod(
        "iframeElement",
        function (value, element) {
            // Check if value is not empty
            if (!value || value.trim().length === 0) {
                return false;
            }

            // Check if it contains iframe tags
            var iframePattern = /<iframe[^>]*>.*?<\/iframe>/i;
            if (!iframePattern.test(value)) {
                return false;
            }

            // Check if iframe has src attribute
            var srcPattern = /<iframe[^>]*src\s*=\s*["']([^"']+)["'][^>]*>/i;
            return srcPattern.test(value);
        },
        "Please enter a valid iframe embed code with a source URL."
    );
});
