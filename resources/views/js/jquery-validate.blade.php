@push('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/additional-methods.min.js"></script>
    <script src="{{ asset('frontend/js/validation.js') }}" type="text/javascript"></script>
    <script>
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

            // Submit form via AJAX
            $.ajax({
                type: "POST",
                url: formElement.attr("action"),
                data: new FormData(form),
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
                },
            });
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
