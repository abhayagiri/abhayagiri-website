import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Gallery from 'react-photo-gallery';
import Lightbox from 'react-images';
import { browserHistory } from 'react-router';

import { withGlobals } from 'components/shared/globals/globals';
import { tp, thp } from 'i18n';
import GalleryService from 'services/gallery.service';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import './album.css';

export class Album extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        setGlobal: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            currentImage: 0,
            album: null,
            smallPhotos: [],
            largePhotos: [],
            isLoading: true
        };
        this.closeLightbox = this.closeLightbox.bind(this);
        this.openLightbox = this.openLightbox.bind(this);
        this.gotoNext = this.gotoNext.bind(this);
        this.gotoPrevious = this.gotoPrevious.bind(this);
    }

    componentDidMount() {
        this.getAlbum(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getAlbum(nextProps);
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
        }, () => {
            props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
    }

    getBreadcrumbs = () => {
        const { album } = this.state;
        return [
            {
                title: tp(album, 'title'),
                to: '/gallery/' + album.id + '-' + album.slug
            }
        ];
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
                        <Lightbox images={this.state.largePhotos}
                            onClose={this.closeLightbox}
                            onClickPrev={this.gotoPrevious}
                            onClickNext={this.gotoNext}
                            currentImage={this.state.currentImage}
                            isOpen={this.state.lightboxIsOpen}
                        />
                    </div>
                </div >
            </div>
        )
    }
}

export default withGlobals(Album);
