@push('css')
    <!-- Link to Choices.js documentation for reference: https://choices-js.github.io/Choices/ -->
    <!-- Include Choices.js CSS for enhanced select boxes -->
    <link href="{{ asset('backend/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('js')
    <!-- Include Choices.js JavaScript for enhanced select box functionality -->
    <script src="{{ asset('backend/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Initialize the Choices.js -->
    <script>
        function initializeChoices(selectors, customOptions = {}) {
            const ChoicesDefaultOptions = {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: null,
                allowHTML: true
            };
            const ChoicesfinalOptions = {
                ...ChoicesDefaultOptions,
                ...customOptions
            };

            // Convert string selectors to array if needed
            const ChoicesSelectorArray = typeof selectors === 'string' ?
                selectors.split(',').map(s => s.trim()) :
                Array.isArray(selectors) ? selectors : [];

            ChoicesSelectorArray.forEach(selector => {
                const ChoicesElement = document.querySelector(selector);
                if (!ChoicesElement) {
                    console.warn(`Element with selector ${selector} not found.`);
                    return;
                }

                // Initialize Choices instance
                new Choices(ChoicesElement, ChoicesfinalOptions);

                // Listen for changes on the original select element to clear error messages
                ChoicesElement.addEventListener('change', function() {
                    const errorMessage = this.closest('.form-group')?.querySelector('.error-block');
                    if (errorMessage && errorMessage.textContent.trim() !== '') {
                        errorMessage.textContent = '';
                    }
                });
            });
        }
    </script>
@endpush
