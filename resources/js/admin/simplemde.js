var localSimplemdeAttributes

function showError(message) {
    alert(message);
}

function getEmbedConfiguration() {
    return {
        name: 'custom-embed',
        action: function (editor) {
            var url = prompt(
                'Please enter a URL to embed.\n\n' +
                'Examples:\n' +
                '- YouTube: https://youtu.be/m02JGV8_WZg\n' +
                '- Gallery: https://www.abhayagiri.org/gallery/228-winter-retreat-2020\n' +
                '- Talk: https://www.abhayagiri.org/talks/7339-freedom-from-fear-anxiety');

            if (url == null) {
                return;
            }

            axios
                .post(`/admin/api/validate-url`, { url })
                .then(function (response) {
                    if (response.data.valid) {
                        const pos = editor.codemirror.getCursor();
                        editor.codemirror.replaceSelection(`[!embed](${url})`, pos);
                        return;
                    }

                    showError(`We were unable to create valid embed text based on your provided URL. You entered ${url}`);
                }).catch(function (error) {
                    showError(`An unexpected error occurred while processing the url (${url}). [${error}]`);
                });
        },
        className: 'la la-film',
        title: 'Embed Video, Gallery or Talk'
    };
}

function getPreviewRenderConfiguration() {
    return function (plainText, preview) {
        axios
            .post(`/admin/api/render-markdown`, { text: plainText })
            .then(function (response) {
                if (response.data.html) {
                    preview.innerHTML = response.data.html;
                }
            }).catch(function (error) {
                preview.innerHTML = `An unexpected error occurred while attempting to render the html from markdown. [${error}]`;
            });

        return null;
    };
}

export function extendSimplemdeAttributes(configurationObject) {
    configurationObject = Object.assign(configurationObject, {
        toolbar: [
            'bold', 'italic', 'heading', '|',
            'quote', 'unordered-list', 'ordered-list', '|',
            'link', 'image', getEmbedConfiguration(), '|',
            'preview', 'side-by-side', 'fullscreen', '|',
            'guide'
        ],
        previewRender: getPreviewRenderConfiguration(),
    });
    return configurationObject;
}
