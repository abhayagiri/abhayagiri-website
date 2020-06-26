<template>
    <div :class="['gallery-manager', this.state]"></div>
</template>

<script>

import axios from 'axios';

import 'lightgallery.js';
import 'lg-autoplay.js';
import 'lg-fullscreen.js';
import 'lg-zoom.js';

export default {
    created() {
        this.$nextTick(function () {
            // TODO Alternative to jQuery for event/observing?
            $('a.lightbox').click((function (e) {
                if (e.ctrlKey || e.metaKey) {
                    // Don't fire on control-click
                    return;
                }
                const t = e.currentTarget;
                const galleryId = parseInt(t.getAttribute('data-gallery-id')) || null;
                const index = parseInt(t.getAttribute('data-gallery-index')) || 0;
                if (galleryId) {
                    e.preventDefault();
                    this.show(galleryId, index);
                }
            }).bind(this));
        });
    },

    data() {
        return {
            galleryId: null,
            index: null,
            photos: null,
            state: 'ready'
        }
    },

    methods: {
        getDynamicEl() {
            if (!this.photos) {
                return null;
            }
            return this.photos.map(function (photo) {
                // TODO localize
                const caption = window.Locale === 'th' ?
                    (photo.caption_th || photo.caption_en) : photo.caption_en;
                const subHtml = (caption) ?
                    ('<h4>' + _.escape(caption) + '</h4>') : null;

                const images = ['small', 'medium', 'large'].map(function (type) {
                    return {
                        type: type,
                        url: photo[type + '_url'],
                        width: photo[type + '_width'],
                        height: photo[type + '_height'],
                    };
                }).filter(function (image) {
                    return image.type === 'large' ||
                           Math.min(image.width, image.height) >= 400;
                });

                return {
                    src: images[images.length - 1].url,
                    width: images[images.length - 1].width,
                    responsive:
                        images.map(function (image) {
                            return image.url + ' ' + image.width;
                        }).join(', '),
                    downloadUrl: photo.original_url,
                    subHtml: subHtml
                };
            });
        },

        createLightGallery() {
            if (!this.photos) {
                return;
            }
            let node = document.createElement('div');
            node.addEventListener('onCloseAfter', function () {
                // https://github.com/sachinchoolur/lightGallery/issues/155
                document.documentElement.classList.remove('lg-on');
                const lgUid = node.getAttribute('lg-uid');
                const plugin = window.lgData[lgUid];
                if (plugin) {
                    plugin.destroy(true);
                } else {
                    console.warn('Could not find LightGallery to destroy');
                }
            });
            node.addEventListener('onAfterOpen', function () {
                // https://github.com/sachinchoolur/lightGallery/issues/155
                document.documentElement.classList.add('lg-on');
            });
            window.lightGallery(node, {
                closable: false,
                dynamic: true,
                dynamicEl: this.getDynamicEl(),
                hideBarsDelay: 5000,
                index: this.index,
                mode: 'lg-fade',
                pause: 10000, // autoplay
                preload: 1,
                progressBar: false,
                speed: 600,
                startClass: 'lg-fade'
            });
        },

        show(galleryId, index) {
            if (this.state !== 'ready') {
                return;
            }
            galleryId = parseInt(galleryId) || null;
            index = parseInt(index || 0);
            if (this.galleryId && this.photos && this.galleryId == galleryId) {
                this.index = index;
                this.createLightGallery();
                return;
            }
            this.state = 'transitioning';
            setTimeout((function() {
                this.state = 'loading';
            }).bind(this), 10);

            axios.get('/api/albums/' + galleryId)
                .then((function (response) {
                    if (Array.isArray(response.data.photos)) {
                        this.galleryId = galleryId;
                        this.index = index;
                        this.photos = response.data.photos;
                        this.createLightGallery();
                    } else {
                        console.warn('Unexpected response data');
                    }
                }).bind(this))
                .finally((function () {
                    setTimeout((function() {
                        this.state = 'transitioning';
                        setTimeout((function() {
                            this.state = 'ready';
                        }).bind(this), 10);
                    }).bind(this), 500);
                }).bind(this));
        }
    }
}
</script>
