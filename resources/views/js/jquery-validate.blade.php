@push('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/additional-methods.min.js"></script>
    <script src="{{ asset('frontend/js/validation.js') }}" type="text/javascript"></script>
    <script>
        // Callback when reCAPTCHA is successfully checked
        window.onRecaptchaSuccess = function() {
            $('#recaptcha-error').text('');
        };

        //email form validation
        function setupValidation(formId, rules = {}, messages = {}, successCallback = null, onlyValidation = false,
            submitAction = null, modalFunction = null, resetFormAfterSuccess = true) {
            $(formId).validate({
                ignore: [],
                rules: rules,
                messages: messages,
                errorPlacement: function(error, element) {
                    if ($(element).closest('.jqv-group').find('.help-block')) {
                        $(element).closest('.jqv-group')
                            .find('.help-block').text(error.text());
                    } else {
                        error.insertAfter(element);
                    }
                },
                // Prevent duplicate error messages
                showErrors: function(errorMap, errorList) {
                    if (this.currentForm.id === 'newsLetterForm') {
                        // Custom logic for #newsLetterForm
                        $('.help-block').text(''); // Clear all help blocks
                        if (errorList.length) {
                            const error = errorList[0];
                            const group = $(error.element).closest('.jqv-group');
                            group.find('.help-block').text(error.message);
                        }
                    } else {
                        // Default behavior for all other forms
                        this.defaultShowErrors();
                    }
                },
                success: function(label, element) {
                    $(element).closest('.jqv-group')
                        .find('.help-block').text("");
                },
                onfocusout: function(element) {
                    element.value = $.trim(element.value); // Trim whitespace
                    if (!this.optional(element)) {
                        this.element(element);
                    }
                },
                submitHandler: function(form, event) {
                    if (onlyValidation) {
                        submitAction(form, event);
                        return false;
                    } else {
                        submitForm(form, successCallback, modalFunction, resetFormAfterSuccess);
                    }
                },
            });
        }

        function submitForm(form, successCallback, modalFunction, resetFormAfterSuccess) {
            const formElement = $(form);
            let submitBtn = formElement.find(":submit:visible");

            if (submitBtn.length === 0) {
                submitBtn = $('button[type="submit"][form="' + formElement.attr('id') + '"]');
            }

            const originalText = submitBtn.html();
            const btnSavingName = submitBtn.attr('data-saving-name') ?? 'Please wait...';

            // Reset any previous errors
            formElement.find('.help-block').html('');
            formElement.find('.formSuccessMsg').html('');
            formElement.find('.formErrorMsg').html('');
            formElement.find('.form-error-message').addClass('d-none').html('');

            // Show loading state
            submitBtn.prop("disabled", true).html(
                '<div class="spinner-border spinner-border-sm text-white me-2" role="status"></div><span>' +
                btnSavingName + '...</span>'
            );

            const sendAjax = function() {
                const formData = new FormData(form);
                if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.getResponse === 'function') {
                    const token = grecaptcha.getResponse();
                    if (token) {
                        formData.set('g-recaptcha-response', token);
                    }
                }

                // Submit form via AJAX
                $.ajax({
                    type: "POST",
                    url: formElement.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            if (response.needPageReload == false) {
                                if (response.showModal) {
                                    $('#' + response.modalId).modal('show');
                                    if (resetFormAfterSuccess) {
                                        resetButton(submitBtn, originalText);
                                        formElement[0].reset();
                                    }
                                    if (modalFunction) {
                                        modalFunction();
                                    }
                                } else {
                                    showSuccess(formElement, response.message ??
                                        "form submitted successfully");
                                    resetButton(submitBtn, originalText);
                                }
                            } else {
                                if (resetFormAfterSuccess) {
                                    formElement[0].reset();
                                }
                                resetButton(submitBtn, originalText);
                                if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                                    try { grecaptcha.reset(); } catch(e) {}
                                }
                                if (response.hasOwnProperty('showSuccess') && response.showSuccess === false) {
                                    // Don't show toast when showSuccess is explicitly false
                                    console.log('Toast suppressed - showSuccess is false');
                                } else {
                                    showToast('success', response.message);
                                }
                            }

                            if (successCallback) {
                                successCallback(response);
                            }
                        } else {
                            resetButton(submitBtn, originalText);
                            if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                                try { grecaptcha.reset(); } catch(e) {}
                            }
                            Swal.fire({
                                text: response.message ??
                                    "something went wrong please try again",
                                icon: "warning",
                                buttonsStyling: !1,
                                confirmButtonText: "ok got it",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                },
                            });
                        }
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr, formElement);
                        resetButton(submitBtn, originalText);
                        if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                            try { grecaptcha.reset(); } catch(e) {}
                        }
                    },
                });
            };

            const recaptchaWidget = formElement.find('.g-recaptcha');
            const recaptchaInput = formElement.find('input[name="g-recaptcha-response"]');
            const recaptchaSiteKey = "{{ config('services.recaptcha.site_key') }}";

            // If visible reCAPTCHA v2 widget exists
            if (recaptchaWidget.length > 0 && typeof grecaptcha !== 'undefined') {
                const recaptchaResponse = grecaptcha.getResponse();
                if (!recaptchaResponse || recaptchaResponse.length === 0) {
                    formElement.find('#recaptcha-error').text('Please verify that you are not a robot.');
                    resetButton(submitBtn, originalText);
                    return false;
                } else {
                    formElement.find('#recaptcha-error').text('');
                    sendAjax();
                }
            } else if (recaptchaInput.length > 0 && recaptchaSiteKey && typeof grecaptcha !== 'undefined' && typeof grecaptcha.execute === 'function') {
                grecaptcha.ready(function() {
                    grecaptcha.execute(recaptchaSiteKey, { action: 'enquiry_form' }).then(function(token) {
                        recaptchaInput.val(token);
                        sendAjax();
                    }).catch(function(err) {
                        console.error('reCAPTCHA execution error:', err);
                        sendAjax();
                    });
                });
            } else {
                sendAjax();
            }
        }

        function resetButton(button, originalText) {
            button.prop("disabled", false).html(originalText);
        }

        function showError(formElement, message) {
            if (formElement.find('.form-error-message').length > 0)
                formElement.find('.form-error-message').removeClass('d-none').html(message);
            else
                formElement.find('.formErrorMsg').html(message);
        }

        function showSuccess(formElement, message) {
            if (formElement.find('formSuccessMsg').length > 0) {
                formElement.find('.formSuccessMsg').html(message);
                setTimeout(() => formElement.find('.formSuccessMsg').html(''), 5000);
            } else {
                Swal.fire({
                    text: message,
                    icon: "success",
                    buttonsStyling: !1,
                    confirmButtonText: "ok got it",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary"
                    },
                });
            }
        }

        function handleAjaxError(xhr, formElement) {
            if (xhr.status === 422) {
                $.each(xhr.responseJSON.errors, function(field, messages) {
                    $('[name="' + field + '"]')
                        .closest(".jqv-group")
                        .find(".help-block")
                        .text(messages.join(", "));
                });
            } else {
                console.error("Unexpected error:", xhr.responseText || xhr.statusText);
            }
        }
    </script>
@endpush
