import React, { Component } from 'react';
import Gallery from 'react-photo-gallery';

import { pageWrapper } from '../shared/util';
import GalleryService from '../services/gallery.service';
import Pagination from '../shared/pagination';
import Link, { localizePathname } from '../shared/link';
import Card from '../shared/card';
import Spinner from '../shared/spinner';

const renderAlbumCover = ({ index, photo }) => {

    return (
        <Link to={photo.path} key={index}>
            <div className="album-cover">
                <div className="album-image">
                    <img style={{ "padding": "5px" }} width={photo.width} height={photo.height} src={photo.src} />
                </div>
                <div className="album-title">{photo.caption}</div>
            </div>
        </Link >
    )
};

class AlbumList extends Component {

    constructor(props, context) {
        super(props, context);

        this.state = {
            albums: [],
            totalPages: 0,
            isLoading: true,
            initialLoad: true
        }
    }

    async getAlbums(props) {
        this.setState({
            isLoading: true
        });

        let response = await GalleryService.getAlbums({
            page: props.pageNumber,
            pageSize: 9
        });

        let albums = response.albums.map(album => {
            return {
                src: album.thumbnail.smallUrl,
                width: album.thumbnail.smallWidth,
                height: album.thumbnail.smallHeight,
                caption: props.tp(album, 'title'),
                path: '/gallery/' + album.id + '-' + album.slug
            }
        })

        this.setState({
            albums: albums,
            totalPages: response.totalPages,
            isLoading: false,
            initialLoad: false
        });
    }

    componentDidMount() {
        this.getAlbums(this.props);
    }

    render() {
        return (
            <div>
                <div className={'album-list container content ' + (this.state.isLoading && 'loading')}>
                    <div className='spinner'>
                        <Spinner />
                    </div>
                    <div className='albums'>
                        <Gallery photos={this.state.albums} renderImage={renderAlbumCover} />
                    </div>
                    <div className='albums-footer'>
                        {!this.state.initialLoad && <Pagination totalPages={this.state.totalPages} />}
                    </div>
                </div>
            </div>
        );
    }
}

export default pageWrapper()(AlbumList);
