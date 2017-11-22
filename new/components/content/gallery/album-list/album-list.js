import React, { Component } from 'react';
import { tp, thp } from '../../../../i18n';
import GalleryService from 'services/gallery.service';
import Pagination from 'components/shared/pagination/pagination';

import Link from 'components/shared/link/link';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import FilterBar from 'components/shared/filters/filter-bar/filter-bar.js';
import Gallery from 'react-photo-gallery';

import './album-list.css';

const AlbumCover = ({ index, onClick, photo }) => {

    console.log(photo);

    return (
        <Link to={'/gallery/' + photo.alt}>
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
    constructor(props) {
        super(props);

        this.state = {
            albums: [],
            isLoading: true,
            initialLoad: true
        }
    }

    async getAlbums(props) {
        this.setState({
            isLoading: true
        });

        let response = await GalleryService.getAlbums({
            searchText: props.location.query.q,
            page: props.location.query.p,
            pageSize: 9
        });0

        let albums = response.albums.map(album => {
            return {
                src: album.thumbnail.smallUrl,
                width: album.thumbnail.smallWidth,
                height: album.thumbnail.smallHeight,
                caption: tp(album, 'title'),
                alt: album.id
            }
        })

        this.setState({
            albums: albums,
            totalPages: response.totalPages,
            isLoading: false,
            initialLoad: false
        });
    }

    componentWillMount() {
        this.getAlbums(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getAlbums(nextProps);
    }

    render() {
        return (
            <div>
                <FilterBar links={[]} />
                <div className={'album-list container content ' + (this.state.isLoading && 'loading')}>
                    <div className='spinner'>
                        <Spinner />
                    </div>
                    <Gallery photos={this.state.albums} ImageComponent={AlbumCover} />
                    {/* 
                    <div className="albums">
                        {this.state.albums.map((album, index) => {
                            return (<div key={index} className="gallery">
                                <Card
                                    thumbnail={album.thumbnail.smallUrl}
                                    href={'/gallery/' + album.id}
                                    title={tp(album, 'title')}
                                    listItem={true}
                                />
                            </div>
                            )
                        })}
                    </div > */}
                    <div className='albums-footer'>
                        {!this.state.initialLoad && <Pagination totalPages={this.state.totalPages} />}
                    </div>
                </div>
            </div>
        );
    }
}

AlbumList.contextTypes = {
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default AlbumList;
