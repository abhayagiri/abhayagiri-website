import React, { Component } from 'react';
import GalleryService from 'services/gallery.service';
import Pagination from 'components/shared/pagination/pagination';

import Link from 'components/shared/link/link';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import FilterBar from 'components/shared/filters/filter-bar/filter-bar.js';

import './album.css';

class AlbumList extends Component {
    constructor() {
        super();
        this.state = {
            albums: [],
            isLoading: true
        }
    }

    async getAlbums(props) {
        this.setState({
            isLoading: true
        });

        let response = await GalleryService.getAlbums({
            searchText: props.location.query.q,
            page: props.location.query.p,
            pageSize: 10
        });

        this.setState({
            albums: response.albums,
            totalPages: response.totalPages,
            isLoading: false
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
                <div className={'container content ' + (this.state.isLoading && 'loading')}>
                    <div className='spinner'>
                        <Spinner />
                    </div>
                    <div className="album-list">
                        {this.state.albums.map(album => {
                            return (<div className="gallery">
                                <Card
                                    thumbnail={album.thumbnail.smallUrl}
                                    href={'./' + album.id}
                                    title={album.titleEn}
                                    listItem={true}
                                />

                            </div>
                            )
                        })}
                    </div >
                    <div className='album-list-footer'>
                        <Pagination totalPages={this.state.totalPages} />
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
