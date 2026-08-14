@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/css/intlTelInput.css">
@endpush

@push('js')
    <!-- Core intlTelInput library (adds window.intlTelInput) -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/js/intlTelInput.min.js"></script>

    <script>
        function initializePhoneInputs(selector = '.phone_number', countryCodeSelector = '.country_code', initialCountry =
            'in', preferredCountries = ['in']) {
            $(selector).each(function(index) {
                const $input = $(this);
                const $form = $input.closest('form');
                let $countryCodeInput = $form.find(countryCodeSelector).eq(index);

                if (!$countryCodeInput.length) {
                    $countryCodeInput = $form.find(countryCodeSelector).eq(0);
                }

                const iti = window.intlTelInput($input[0], {
                    initialCountry: initialCountry,
                    separateDialCode: true,
                    nationalMode: true,
                    strictMode: true,
                    preferredCountries: preferredCountries,
                    autoPlaceholder: "aggressive",
                    loadUtils: () => import(
                        "https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/js/utils.js"),
                });

                // Save instance for validation later
                $input.data('iti', iti);

                // Set initial country code
                $countryCodeInput.val("+" + iti.getSelectedCountryData().dialCode);

                // Update on country change
                $input.on("countrychange", function() {
                    $countryCodeInput.val("+" + iti.getSelectedCountryData().dialCode);
                });

                // Cleanup on blur
                $input.on('blur', function() {
                    var nationalNumber = iti.getNumber(window.intlTelInput.utils.numberFormat.NATIONAL);

                    // remove leading zero
                    if (nationalNumber[0] === '0') {
                        nationalNumber = nationalNumber.slice(1);
                    }

                    const cleaned = nationalNumber.replace(/[\s\-()]/g, '');
                    $input.val(cleaned);
                });
            });
        }

        initializePhoneInputs();
    </script>
@endpush
