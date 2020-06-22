import React, { Component } from 'react';
import Gallery from 'react-photo-gallery';

import { tp, thp } from 'i18n';
import { getPage } from 'components/shared/location';
import GalleryService from 'services/gallery.service';
import Pagination from 'components/shared/pagination/pagination';
import Link, { localizePathname } from 'components/shared/link/link';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import './album-list.css';

const AlbumCover = ({ index, onClick, photo }) => {

    return (
        <Link to={photo.path}>
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
            page: getPage(),
            pageSize: 9
        });

        let albums = response.albums.map(album => {
            return {
                src: album.thumbnail.smallUrl,
                width: album.thumbnail.smallWidth,
                height: album.thumbnail.smallHeight,
                caption: tp(album, 'title'),
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

    componentWillReceiveProps(nextProps) {
        this.getAlbums(nextProps);
    }

    render() {
        return (
            <div>
                <div className={'album-list container content ' + (this.state.isLoading && 'loading')}>
                    <div className='spinner'>
                        <Spinner />
                    </div>
                    <div className='albums'>
                        <Gallery photos={this.state.albums} ImageComponent={AlbumCover} />
                    </div>
                    <div className='albums-footer'>
                        {!this.state.initialLoad && <Pagination totalPages={this.state.totalPages} />}
                    </div>
                </div>
            </div>
        );
    }
}

export default AlbumList;
