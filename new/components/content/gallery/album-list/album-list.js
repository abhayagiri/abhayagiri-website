import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Gallery from 'react-photo-gallery';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { withGlobals } from 'components/shared/globals/globals';
import { tp, thp } from 'i18n';
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

    static propTypes = {
        location: PropTypes.object.isRequired,
        setBreadcrumbs: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);

        this.state = {
            albums: [],
            totalPages: 0,
            isLoading: true,
            initialLoad: true
        }

        this.searchTo = this.searchTo.bind(this);
    }

    async getAlbums(props) {
        this.setState({
            isLoading: true
        });

        let response = await GalleryService.getAlbums({
            searchText: props.location.query.q,
            page: props.location.query.p,
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
        this.props.setBreadcrumbs();
        this.getAlbums(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getAlbums(nextProps);
    }

    searchTo(searchText) {
        return {
            pathname: localizePathname('/gallery'),
            query: { q: searchText }
        };
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

export default withBreadcrumbs(withGlobals(AlbumList, 'location'));
