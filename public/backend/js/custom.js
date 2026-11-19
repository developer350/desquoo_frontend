$(function () {
    // CSRF token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    /**
     * Attach a click event handler to delete buttons
     */
    $("body").on("click", ".delete-btn", function (event) {
        event.preventDefault();

        const $button = $(this);
        const tableId = $button.closest("table").attr("id"); // Get the DataTable ID

        const deleteMessage =
            $button.data("delete-message-type") === "itemWithRelated"
                ? "This will delete the item and its related items!"
                : "This will delete the item!";

        Swal.fire({
            title: "Are you sure?",
            text: deleteMessage,
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#2ab57d",
            cancelButtonColor: "#fd625e",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $button.prop("disabled", true);

                $.ajax({
                    url: $button.closest("form").attr("action"),
                    type: "POST",
                    data: $button.closest("form").serialize(),
                    success: function (response) {
                        showToast(
                            response.message,
                            response.success ? "success" : "error"
                        );
                        if (response.success) {
                            if (response.data && response.data.redirect) {
                                setTimeout(function () {
                                    window.location.href =
                                        response.data.redirect;
                                }, 1000);
                            } else {
                                $("#" + tableId)
                                    .DataTable()
                                    .ajax.reload(null, false); // Preserve pagination
                            }
                        } else {
                            if (response.is_depended == true) {
                                Swal.fire({
                                    title: "this item is depended",
                                    text: response.data.message,
                                    icon: "warning",
                                    showCancelButton: !0,
                                    confirmButtonColor: "#2ab57d",
                                    cancelButtonColor: "#fd625e",
                                    confirmButtonText: "Yes, Force delete it!",
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        var data = $button
                                            .closest("form")
                                            .serialize();
                                        data += "&force_delete=true";

                                        $.ajax({
                                            url: $button
                                                .closest("form")
                                                .attr("action"),
                                            type: "POST",
                                            data: data,
                                            success: function (response) {
                                                showToast(
                                                    response.message,
                                                    response.success
                                                        ? "success"
                                                        : "error"
                                                );
                                                if (response.success) {
                                                    if (
                                                        response.data &&
                                                        response.data.redirect
                                                    ) {
                                                        setTimeout(function () {
                                                            window.location.href =
                                                                response.data.redirect;
                                                        }, 1000);
                                                    } else {
                                                        $("#" + tableId)
                                                            .DataTable()
                                                            .ajax.reload(
                                                                null,
                                                                false
                                                            ); // Preserve pagination
                                                    }
                                                }
                                            },
                                        });
                                    }
                                });
                            }
                        }
                    },
                    error: function (xhr) {
                        showToast(
                            "An error occurred. Please try again.",
                            "error"
                        );
                        console.error(
                            "Unexpected error:",
                            xhr.responseText || xhr.statusText
                        );
                    },
                    complete: function () {
                        $button.prop("disabled", false);
                    },
                });
            }
        });
    });

    /**
     * Manages the input and change events for elements with the class `.sort-order`,
     * ensuring that only valid numeric input is processed and updating the server with
     * the new sort order value via AJAX with a debounce mechanism.
     */
    let sortOrderDebounceTimer;

    $(document).on("input change", ".sort-order", function (event) {
        const $this = $(this);
        let inputValue = $this.val().replace(/[^\d]/g, ""); // Remove non-digit characters
        let value = Math.max(
            0,
            Math.min(parseInt(inputValue, 10) || 0, 2147483647)
        ); // Validate and limit range
        $this.val(value);

        // Only trigger AJAX request on change event
        if (event.type === "change") {
            clearTimeout(sortOrderDebounceTimer);

            sortOrderDebounceTimer = setTimeout(() => {
                $.ajax({
                    url: `/${prefix}/admin-settings/update-sort-order`,
                    type: "POST",
                    data: {
                        id: $this.data("id"),
                        model: $this.data("model"),
                        value: value,
                    },
                    success(response) {
                        showToast(
                            response.success
                                ? "Sort Order Updated successfully"
                                : "Failed to Update Sort Order",
                            response.success ? "success" : "error"
                        );
                    },
                    error: function (xhr) {
                        showToast(
                            "An error occurred while updating Sort Order",
                            "error"
                        );
                        console.error(
                            "Unexpected error:",
                            xhr.responseText || xhr.statusText
                        );
                    },
                });
            }, 300); // Debounce time
        }
    });

    /**
     * On clicking `.form-modal-btn`, loads form HTML via AJAX from `data-url`,
     * injects it into the modal specified by `data-target`, then shows the modal.
     * Initializes form validation with redirect URL from `data-redirect-url`.
     * Logs errors to console on failure.
     */
    $(document).on("click", ".form-modal-btn", function () {
        const formUrl = $(this).data("form-url");
        const modal = $($(this).data("target"));
        const redirectUrl = $(this).data("redirect-url");
        const afterModal = $(this).data("after-modal-show");

        $.ajax({
            url: formUrl,
            method: "GET",
            success(response) {
                modal.find(".modal-content").html(response);
                modal.modal("show");

                if (afterModal && typeof window[afterModal] === "function") {
                    window[afterModal]();
                }

                $("form").customValidate({ successRoute: redirectUrl });
            },
            error(xhr, status, error) {
                console.error("Failed to load form:", error);
            },
        });
    });

    /**
     * Handles toggle switch changes with debouncing to prevent rapid AJAX requests.
     */
    var toggleSwitchDebounceTimer;

    $(document).on("change", ".toggle-switch", function () {
        clearTimeout(toggleSwitchDebounceTimer);

        const $this = $(this);
        const value = $this.is(":checked") ? 1 : 0;
        const { model, column, id, labels, name } = $this.data();
        const [labelSuccess, labelFailed] = labels.split(";");
        const $label = $this.siblings("label");

        $label.text(value === 1 ? labelSuccess : labelFailed);

        toggleSwitchDebounceTimer = setTimeout(() => {
            $.ajax({
                url: `/${prefix}/admin-settings/update-toggle-status`,
                type: "POST",
                data: { id, model, column, value },
                success(response) {
                    if (response.success)
                        showToast(`${name} updated successfully`, "success");
                    else
                        revertToggleSwitch(
                            $this,
                            value,
                            labelFailed,
                            labelSuccess,
                            name
                        );
                },
                error: function (xhr) {
                    revertToggleSwitch(
                        $this,
                        value,
                        labelFailed,
                        labelSuccess,
                        name
                    );
                    console.error(
                        "Unexpected error:",
                        xhr.responseText || xhr.statusText
                    );
                },
            });
        }, 300);
    });

    function revertToggleSwitch(
        $element,
        value,
        labelFailed,
        labelSuccess,
        name
    ) {
        $element.prop("checked", !value);
        $element
            .siblings("label")
            .text(value === 1 ? labelFailed : labelSuccess);
        showToast(`Failed to Update ${name}`, "error");
    }

    /**
     * Toggles 'scroll-active' class on '.fixed-action-card' based on window scroll position (80px threshold).
     */
    const actionCard = $(".fixed-action-card");
    const scrollPoint = 80;

    $(window).scroll(function () {
        if ($(this).scrollTop() > scrollPoint) {
            actionCard.addClass("scroll-active");
        } else {
            actionCard.removeClass("scroll-active");
        }
    });

    // Initialize standard textarea live counters
    initStandardTextareaCounters();
});

/**
 * Shared HTML generator for live textarea & editor counter badge
 */
window.createTextareaCounterBadgeHTML = function(maxChars) {
    const hasMax = maxChars && parseInt(maxChars, 10) > 0;
    return `
        <div class="textarea-counter-badge state-normal" title="Real-time character and word count">
            <svg class="counter-progress-ring" viewBox="0 0 20 20" width="14" height="14" style="${hasMax ? '' : 'display:none;'}">
                <circle cx="10" cy="10" r="7" stroke="#e2e8f0" stroke-width="2.5" fill="none" class="ring-bg"></circle>
                <circle class="progress-bar-circle" cx="10" cy="10" r="7" stroke="#556ee6" stroke-width="2.5" fill="none" stroke-dasharray="44" stroke-dashoffset="44" stroke-linecap="round"></circle>
            </svg>
            <span class="counter-item counter-words">
                <i class="bx bx-file-blank"></i>
                <span class="words-val">0</span> words
            </span>
            <span class="counter-dot">•</span>
            <span class="counter-item counter-chars">
                <i class="bx bx-font"></i>
                <span class="chars-val">0</span><span class="chars-max" style="${hasMax ? '' : 'display:none;'}"> / ${hasMax ? parseInt(maxChars, 10).toLocaleString() : ''}</span> chars
            </span>
        </div>
    `;
};

/**
 * Shared renderer for counter badge updates
 */
window.renderTextareaCounterBadge = function($badge, chars, words, maxChars) {
    if (!$badge || !$badge.length) return;
    $badge.find('.words-val').text(Number(words || 0).toLocaleString());
    $badge.find('.chars-val').text(Number(chars || 0).toLocaleString());

    const max = maxChars ? parseInt(maxChars, 10) : null;
    if (max && max > 0) {
        $badge.find('.chars-max').text(' / ' + max.toLocaleString()).show();
        $badge.find('.counter-progress-ring').show();

        const ratio = chars / max;
        const circumference = 44; // 2 * PI * 7
        const offset = Math.max(0, circumference - (Math.min(ratio, 1) * circumference));

        const $circle = $badge.find('.progress-bar-circle');
        $circle.css('stroke-dashoffset', offset);

        $badge.removeClass('state-normal state-warning state-danger');
        if (ratio >= 1) {
            $badge.addClass('state-danger');
            $circle.attr('stroke', '#ef4444');
        } else if (ratio >= 0.85) {
            $badge.addClass('state-warning');
            $circle.attr('stroke', '#f59e0b');
        } else {
            $badge.addClass('state-normal');
            $circle.attr('stroke', '#556ee6');
        }
    } else {
        $badge.find('.chars-max').hide();
        $badge.find('.counter-progress-ring').hide();
        $badge.removeClass('state-warning state-danger').addClass('state-normal');
    }
};

/**
 * Initializes live counters on standard textareas
 */
window.initStandardTextareaCounters = function() {
    $('textarea:not(.tinymce)').each(function() {
        const $textarea = $(this);
        if ($textarea.data('has-char-counter')) return;

        const maxChars = $textarea.data('rule-maxlength') || $textarea.attr('maxlength') || null;
        if (!maxChars && !$textarea.hasClass('has-counter')) return;

        $textarea.data('has-char-counter', true);

        const $group = $textarea.closest('.form-group');
        if (!$group.length) return;

        let $row = $group.find('.textarea-counter-row');
        if (!$row.length) {
            $row = $('<div class="textarea-counter-row"></div>');
            const $error = $group.find('.error-block');
            if ($error.length) {
                $error.appendTo($row);
            } else {
                $row.append('<span class="error-block"></span>');
            }
            $textarea.after($row);
        }

        let $badge = $row.find('.textarea-counter-badge');
        if (!$badge.length) {
            $badge = $(window.createTextareaCounterBadgeHTML(maxChars));
            $row.append($badge);
        }

        const update = () => {
            const val = $textarea.val() || '';
            const chars = val.length;
            const trimmed = val.trim();
            const words = trimmed ? trimmed.split(/\s+/).length : 0;
            window.renderTextareaCounterBadge($badge, chars, words, maxChars);

            const max = maxChars ? parseInt(maxChars, 10) : null;
            let $error = $group.find('.error-block');
            if (max && chars > max) {
                const label = ($group.find('label').first().text().trim().replace(/\*$/, '') || 'field').toLowerCase();
                $error.text(`The ${label} field must not be greater than ${max} characters.`).show();
                $textarea.addClass('is-invalid');
            } else if (max && chars <= max && $textarea.hasClass('is-invalid') && $error.text().includes('must not be greater than')) {
                $error.text('');
                $textarea.removeClass('is-invalid');
            }
        };

        $textarea.on('input keyup paste change', update);
        update();
    });
};

/**
 * Displays a toast notification using Toastr.
 */
function showToast(message, type = "success", options = {}) {
    toastr.options = {
        closeButton: true,
        progressBar: false,
        positionClass: "toast-top-right",
        timeOut: 5000,
        extendedTimeOut: 2000,
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        preventDuplicates: true,
        newestOnTop: true,
        escapeHtml: false,
        closeEasing: "swing",
        showEasing: "swing",
        hideEasing: "linear",
        tapToDismiss: true,
        onHidden: null,
        debug: false,
        ...options,
    };

    // Ensure a valid toast type is used
    const validTypes = ["success", "error", "warning", "info"];
    const toastType = validTypes.includes(type) ? type : "info";

    // Display the toast notification
    toastr[toastType](message);
}

document.addEventListener("DOMContentLoaded", () => {
    bootstrap.Tooltip.getOrCreateInstance(document.body, {
        selector: '[data-bs-toggle="tooltip"]',
    });
});
