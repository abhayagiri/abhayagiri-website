import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Gallery from 'react-photo-gallery';
import Carousel, { Modal, ModalGateway } from 'react-images';

import { pageWrapper } from '../shared/util';
import GalleryService from '../services/gallery.service';
import Card from '../shared/card';
import Spinner from '../shared/spinner';

export class Album extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
    };
    
    constructor(props, context) {
        super(props, context);
        this.state = {
            currentImage: 0,
            album: null,
            smallPhotos: [],
            largePhotos: [],
            isLoading: true
        };
    }

    componentDidMount() {
        this.getAlbum(this.props);
    }

    async getAlbum(props) {
        let albumId = parseInt(props.params.albumId);
        // Handle old IDs
        // TODO, should redirect from server to proper place.
        if (albumId < 0) {
            albumId = -albumId;
        }
        const album = await GalleryService.getAlbum(albumId);

        let smallPhotos = album.photos.map(photo => {
            return {
                src: photo.smallUrl,
                width: photo.smallWidth,
                height: photo.smallHeight
            }
        });

        let largePhotos = album.photos.map(photo => {
            return {
                source: {
                    download: photo.originalUrl,
                    fullscreen: photo.largeUrl,
                    regular: photo.mediumUrl,
                    thumbnail: photo.smallUrl
                },
                caption: props.tp(photo, 'caption')
            }
        });

        this.setState({
            album: album,
            smallPhotos: smallPhotos,
            largePhotos: largePhotos,
            isLoading: false
        });
    }

    openLightbox = (event, obj) => {
        this.setState({
            currentImage: obj.index,
            lightboxIsOpen: true,
        });
    }

    closeLightbox = (event) => {
        this.setState({
            currentImage: 0,
            lightboxIsOpen: false,
        });
    }

    render() {
        const { tp, thp } = this.props;
        let album = this.state.album;

        return this.state.isLoading ? <Spinner /> : (
            <div className="container content album">
                <div className="row">
                    <div className="col-sm-12">
                        <Card
                            thumbnail={album.thumbnail.smallUrl}
                            title={tp(album, 'title')}
                            listItem={false}
                            body={thp(album, 'descriptionHtml')}
                            landscape={true}
                        />
                    </div>
                </div>

                <div className="row">
                    <div className="col-sm-12">
                        <Gallery photos={this.state.smallPhotos} onClick={this.openLightbox} />
                        <ModalGateway>
                            {this.state.lightboxIsOpen ? (
                                <Modal onClose={this.closeLightbox}>
                                    <Carousel
                                        currentIndex={this.state.currentImage}
                                        views={this.state.largePhotos} />
                                </Modal>
                            ) : null}
                        </ModalGateway>
                    </div>
                </div >
            </div>
        )
    }
}

export default pageWrapper()(Album);
