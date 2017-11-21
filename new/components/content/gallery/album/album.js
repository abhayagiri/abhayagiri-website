import React, { Component } from 'react';
import { tp, thp } from '../../../../i18n';

import Gallery from 'react-photo-gallery';
import Lightbox from 'react-images';
import GalleryService from 'services/gallery.service';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import EventEmitter from 'services/emitter.service';

import './album.css';

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
                src: photo.smallUrl,
                width: photo.smallWidth,
                height: photo.smallHeight
            }
        });

        let largePhotos = album.photos.map(photo => {
            return {
                src: photo.mediumUrl,
                width: photo.mediumWidth,
                height: photo.mediumHeight,
                caption: tp(photo, 'caption'),
            }
        });

        this.setState({
            album: album,
            smallPhotos: smallPhotos,
            largePhotos: largePhotos,
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