@push('js')
    <!-- TinyMCE library -->
    <script src="{{ asset('backend/libs/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
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
                plugins: 'advlist autolink link lists table code image',
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
                    editor.on('input change', function() {
                        let errorMessage = $(editor.getElement()).closest('.form-group')
                            .find('.error-block');
                        if (errorMessage.text().trim()) {
                            errorMessage.text('');
                        }
                    });
                },
                extended_valid_elements : 'i[class],span[class]',
            });
        }

        document.addEventListener('DOMContentLoaded', initTinyMCE);
    </script>
@endpush
