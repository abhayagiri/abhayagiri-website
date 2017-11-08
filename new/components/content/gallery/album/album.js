import React, { Component } from 'react';
import { tp, thp } from '../../../../i18n';

import Gallery from 'react-photo-gallery';
import Lightbox from 'react-images';
import GalleryService from 'services/gallery.service';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import EventEmitter from 'services/emitter.service';

import './album.css';

const photos = [
    { src: 'https://source.unsplash.com/2ShvY8Lf6l0/800x599', width: 4, height: 3 },
    { src: 'https://source.unsplash.com/Dm-qxdynoEc/800x799', width: 1, height: 1 },
    { src: 'https://source.unsplash.com/qDkso9nvCg0/600x799', width: 3, height: 4 },
    { src: 'https://source.unsplash.com/iecJiKe_RNg/600x799', width: 3, height: 4 },
    { src: 'https://source.unsplash.com/epcsn8Ed8kY/600x799', width: 3, height: 4 },
    { src: 'https://source.unsplash.com/NQSWvyVRIJk/800x599', width: 4, height: 3 },
    { src: 'https://source.unsplash.com/zh7GEuORbUw/600x799', width: 3, height: 4 },
    { src: 'https://source.unsplash.com/PpOHJezOalU/800x599', width: 4, height: 3 },
    { src: 'https://source.unsplash.com/I1ASdgphUH4/800x599', width: 4, height: 3 }
];

class Album extends Component {

    constructor() {
        super();
        this.state = {
            currentImage: 0,
            album: null,
            photos: [],
            isLoading: true
        };
        this.closeLightbox = this.closeLightbox.bind(this);
        this.openLightbox = this.openLightbox.bind(this);
        this.gotoNext = this.gotoNext.bind(this);
        this.gotoPrevious = this.gotoPrevious.bind(this);
    }

    componentWillMount() {
        this.getAlbum(this.props.params.albumId);
    }

    async getAlbum(albumId) {
        let album = await GalleryService.getAlbum(albumId);
        
        let smallPhotos = album.photos.map(photo => {
            return {
                src: photo.mediumUrl,
                width: photo.mediumWidth,
                height: photo.mediumHeight
            }
        });

        let largePhotos = album.photos.map(photo => {
            return {
                src: photo.largeUrl,
                width: photo.largeWidth,
                height: photo.largeHeight,
                caption: tp(photo, 'caption'),
            }
        });

        this.setState({
            album: album,
            smallPhotos: smallPhotos,
            largePhotos: smallPhotos,
            isLoading: false
        });

        EventEmitter.emit('breadcrumb', tp(album, 'title'));
    }

    openLightbox(event, obj) {
        this.setState({
            currentImage: obj.index,
            lightboxIsOpen: true,
        });
    }

    closeLightbox() {
        this.setState({
            currentImage: 0,
            lightboxIsOpen: false,
        });
    }

    gotoPrevious() {
        this.setState({
            currentImage: this.state.currentImage - 1,
        });
    }

    gotoNext() {
        this.setState({
            currentImage: this.state.currentImage + 1,
        });
    }

    render() {
        let album = this.state.album;

        return this.state.isLoading ? <Spinner /> : (
            <div className="container content album">

                <Gallery photos={this.state.smallPhotos} onClick={this.openLightbox} />
                <Lightbox images={this.state.largePhotos}
                    onClose={this.closeLightbox}
                    onClickPrev={this.gotoPrevious}
                    onClickNext={this.gotoNext}
                    currentImage={this.state.currentImage}
                    isOpen={this.state.lightboxIsOpen}
                />
            </div>
        )
    }
}

export default Album;