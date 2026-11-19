@push('css')
    <style>
        .textarea-counter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 6px;
            min-height: 26px;
            flex-wrap: wrap;
        }

        .textarea-counter-row .error-block {
            flex: 1 1 auto;
            font-size: 13px;
            font-weight: 500;
            color: #fd625e !important;
            margin: 0;
            line-height: 1.5;
            word-break: break-word;
        }

        .textarea-counter-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 11.5px;
            font-weight: 500;
            line-height: 1;
            padding: 4px 10px;
            border-radius: 9999px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            user-select: none;
            margin-left: auto;
        }

        .textarea-counter-badge .counter-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .textarea-counter-badge .counter-item i {
            font-size: 13px;
            opacity: 0.85;
        }

        .textarea-counter-badge .counter-dot {
            opacity: 0.4;
            font-size: 8px;
        }

        .textarea-counter-badge .counter-progress-ring {
            width: 14px;
            height: 14px;
            transform: rotate(-90deg);
            flex-shrink: 0;
        }

        .textarea-counter-badge .counter-progress-ring circle {
            transition: stroke-dashoffset 0.2s ease, stroke 0.2s ease;
        }

        /* State: Normal (< 85%) */
        .textarea-counter-badge.state-normal {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }

        /* State: Warning (85% - 99%) */
        .textarea-counter-badge.state-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: #b45309;
            box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.15);
        }

        .textarea-counter-badge.state-warning .counter-item i {
            color: #d97706;
        }

        /* State: Danger (>= 100%) */
        .textarea-counter-badge.state-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
            font-weight: 600;
            box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.2);
            animation: counterPop 0.25s ease-out;
        }

        .textarea-counter-badge.state-danger .counter-item i {
            color: #dc2626;
        }

        @keyframes counterPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.06); }
            100% { transform: scale(1); }
        }

        /* Dark Theme Support */
        [data-bs-theme="dark"] .textarea-counter-badge {
            background: #262b3c;
            border-color: #363d52;
            color: #94a3b8;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .textarea-counter-badge.state-normal {
            background: #262b3c;
            border-color: #363d52;
            color: #94a3b8;
        }

        [data-bs-theme="dark"] .textarea-counter-badge .ring-bg {
            stroke: #363d52;
        }

        [data-bs-theme="dark"] .textarea-counter-badge.state-warning {
            background: rgba(245, 158, 11, 0.14);
            border-color: rgba(245, 158, 11, 0.35);
            color: #fbbf24;
        }

        [data-bs-theme="dark"] .textarea-counter-badge.state-danger {
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(239, 68, 68, 0.4);
            color: #f87171;
        }

        /* Hide duplicate TinyMCE statusbar wordcount */
        .tox .tox-statusbar__wordcount {
            display: none !important;
        }
    </style>
@endpush

@push('js')
    <!-- TinyMCE library -->
    <script src="{{ asset('backend/libs/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        // Shared counter helper functions
        if (!window.createTextareaCounterBadgeHTML) {
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
        }

        if (!window.renderTextareaCounterBadge) {
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
        }

        function initTinyMCE() {
            const isDarkMode = document.body.getAttribute("data-bs-theme") === "dark";

            tinymce.remove(); // Remove existing instances before reinitializing

            tinymce.init({
                selector: 'textarea.tinymce',
                license_key: 'gpl',
                branding: false,
                promotion: false,
                height: document.querySelector('textarea.tinymce')?.dataset.height || 400,
                menubar: true,
                plugins: 'advlist autolink link lists table code image wordcount',
                toolbar: 'undo redo | styleselect | blocks | formatselect | fontselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright | forecolor backcolor | bullist numlist | link image table code',
                font_formats: 'Arial=arial,helvetica,sans-serif; Times New Roman=times new roman,times,serif; Courier New=courier new,courier,monospace; Verdana=verdana,geneva,sans-serif; Georgia=georgia,palatino,serif; Tahoma=tahoma,arial,helvetica,sans-serif',
                fontsize_formats: '10pt 12pt 14pt 16pt 18pt 24pt 36pt',
                content_style: "body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }",
                relative_urls: false,
                remove_script_host: false,
                menubar: 'file edit insert format tools table',
                menu: {
                    file: {
                        title: 'File',
                        items: 'newdocument'
                    },
                    edit: {
                        title: 'Edit',
                        items: 'undo redo | cut copy paste | selectall'
                    },
                    insert: {
                        title: 'Insert',
                        items: 'link image table hr'
                    },
                    format: {
                        title: 'Format',
                        items: 'bold italic underline strikethrough | forecolor backcolor | superscript subscript | blockquote | alignleft aligncenter alignright alignjustify | removeformat'
                    },
                    tools: {
                        title: 'Tools',
                        items: 'code'
                    },
                    table: {
                        title: 'Table',
                        items: 'inserttable deletetable | tableprops | cell cellprops mergecells splitcells | row rowprops insertrowbefore insertrowafter deleterow | column columnprops insertcolumnbefore insertcolumnafter deletecolumn'
                    }
                },
                skin: isDarkMode ? 'oxide-dark' : 'oxide',
                content_css: isDarkMode ? 'dark' : 'default',
                setup: function(editor) {
                    editor.on('init', function() {
                        const textarea = editor.getElement();
                        const $textarea = $(textarea);
                        const $group = $textarea.closest('.form-group');
                        const maxChars = $textarea.data('rule-maxlength') || $textarea.attr('maxlength') || null;

                        // Create counter row if not already created
                        let $row = $group.find('.textarea-counter-row');
                        if (!$row.length) {
                            $row = $('<div class="textarea-counter-row"></div>');
                            const $error = $group.find('.error-block');
                            if ($error.length) {
                                $error.appendTo($row);
                            } else {
                                $row.append('<span class="error-block"></span>');
                            }
                            $(editor.getContainer()).after($row);
                        } else {
                            $(editor.getContainer()).after($row);
                        }

                        let $badge = $row.find('.textarea-counter-badge');
                        if (!$badge.length) {
                            $badge = $(window.createTextareaCounterBadgeHTML(maxChars));
                            $row.append($badge);
                        }

                        const syncTextarea = () => {
                            const rawText = (editor.getContent({ format: 'text' }) || '').trim();
                            const hasImage = editor.getContent().indexOf('<img') !== -1;

                            if (!rawText && !hasImage) {
                                // Content is genuinely empty - keep textarea completely empty for required validation
                                textarea.value = '';
                            } else {
                                editor.save();
                            }
                        };

                        const getEditorCounts = () => {
                            let chars = 0;
                            let words = 0;
                            if (editor.plugins && editor.plugins.wordcount) {
                                try {
                                    chars = editor.plugins.wordcount.body.getCharacterCount() || 0;
                                    words = editor.plugins.wordcount.body.getWordCount() || 0;
                                } catch (e) {}
                            }
                            if (chars === 0 && words === 0) {
                                const text = editor.getContent({ format: 'text' }) || '';
                                chars = text.length;
                                const trimmed = text.trim();
                                words = trimmed ? trimmed.split(/\s+/).length : 0;
                            }
                            return { chars, words };
                        };

                        const validateEditor = (counts) => {
                            let $error = $group.find('.error-block');
                            const max = maxChars ? parseInt(maxChars, 10) : null;

                            if (max && counts.chars > max) {
                                $error.text(`The content field must not be greater than ${max} characters.`).show();
                                $textarea.addClass('is-invalid');
                                return false;
                            } else if (counts.chars === 0 && $textarea.prop('required') && $textarea.data('submitted')) {
                                $error.text('The content field is required.').show();
                                $textarea.addClass('is-invalid');
                                return false;
                            } else {
                                $error.text('');
                                $textarea.removeClass('is-invalid');
                                return true;
                            }
                        };

                        const updateEditorCount = () => {
                            syncTextarea();
                            const counts = getEditorCounts();
                            window.renderTextareaCounterBadge($badge, counts.chars, counts.words, maxChars);
                            return counts;
                        };

                        editor.on('input keyup change undo redo SetContent wordCountUpdate', function() {
                            const counts = updateEditorCount();
                            validateEditor(counts);
                        });

                        editor.on('blur', function() {
                            syncTextarea();
                            const counts = getEditorCounts();
                            validateEditor(counts);
                            $(textarea).valid();
                        });

                        // Ensure textarea is synced and validated before form submission
                        const $form = $textarea.closest('form');
                        if ($form.length) {
                            $form.find(':submit').on('click', function(e) {
                                $textarea.data('submitted', true);
                                syncTextarea();
                                const counts = getEditorCounts();
                                if (!validateEditor(counts)) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    return false;
                                }
                            });
                            $form.on('submit', function(e) {
                                $textarea.data('submitted', true);
                                syncTextarea();
                                const counts = getEditorCounts();
                                if (!validateEditor(counts)) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    return false;
                                }
                            });
                        }

                        // Initial count calculation
                        updateEditorCount();
                    });
                },
                extended_valid_elements : 'i[class],span[class]',
            });
        }

        document.addEventListener('DOMContentLoaded', initTinyMCE);
    </script>
@endpush
