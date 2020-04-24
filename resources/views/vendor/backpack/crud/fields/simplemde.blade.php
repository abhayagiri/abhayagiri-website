<!-- Simple MDE - Markdown Editor -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <textarea
    	id="simplemde_{{ $field['name'] }}"
        name="{{ $field['name'] }}"
        data-init-function="bpFieldInitSimpleMdeElement"
        data-simplemdeAttributesRaw="{{ isset($field['simplemdeAttributesRaw']) ? "{".$field['simplemdeAttributesRaw']."}" : "{}" }}"
        data-simplemdeAttributes="{{ isset($field['simplemdeAttributes']) ? json_encode($field['simplemdeAttributes']) : "{}" }}"
        @include('crud::inc.field_attributes', ['default_class' => 'form-control'])
    	>{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <link rel="stylesheet" href="{{ asset('packages/simplemde/dist/simplemde.min.css') }}">
        <style type="text/css">
        .CodeMirror-fullscreen, .editor-toolbar.fullscreen {
            z-index: 9999 !important;
        }
        .CodeMirror{
        	min-height: auto !important;
        }
        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script src="{{ asset('packages/simplemde/dist/simplemde.min.js') }}"></script>
        <script>
            function bpFieldInitSimpleMdeElement(element) {
                var elementId = element.attr('id');
                var simplemdeAttributes = JSON.parse(element.attr('data-simplemdeAttributes'));
                var simplemdeAttributesRaw = JSON.parse(element.attr('data-simplemdeAttributesRaw'));
                var configurationObject = {
                    element: $('#'+elementId)[0],
                };

                configurationObject = Object.assign(configurationObject, simplemdeAttributes, simplemdeAttributesRaw, {
                    toolbar: [
                        'bold', 'italic', 'heading', '|',
                        'quote', 'unordered-list', 'ordered-list', '|',
                        'link', 'image', '|',
                        'preview', 'table', 'fullscreen', '|',
                        'guide', '|',
                        {
                            name: 'custom-embed-youtube',
                            action: function(editor) {
                                const videoId = prompt('Please enter the YouTube video ID.');

                                if (videoId) {
                                    const pos = editor.codemirror.getCursor();
                                    editor.codemirror.replaceSelection(`[!embed](https://youtu.be/${videoId})`, pos);
                                }
                            },
                            className: 'fa fa-youtube',
                            title: 'Embed YouTube'
                        },
                        {
                            name: 'custom-embed-album',
                            action: function(editor) {
                                const galleryId = prompt('Please enter the ID # for the gallery.');

                                if (galleryId) {
                                    const pos = editor.codemirror.getCursor();
                                    editor.codemirror.replaceSelection(`[!embed](/gallery/${galleryId})`, pos);
                                }
                            },
                            className: 'fa fa-image',
                            title: 'Embed Album'
                        }
                    ]
                });

                var smdeObject = new SimpleMDE(configurationObject);

                smdeObject.options.minHeight = smdeObject.potions && smdeObject.potions.minHeight || "300px";
                smdeObject.codemirror.getScrollerElement().style.minHeight = smdeObject.options.minHeight;
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    setTimeout(function() { smdeObject.codemirror.refresh(); }, 10);
                });
            }
        </script>
    @endpush

@endif

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
