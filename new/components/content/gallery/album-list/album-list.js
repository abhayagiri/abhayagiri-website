import React, { Component } from 'react';
import GalleryService from 'services/gallery.service';
import Pagination from 'components/shared/pagination/pagination';

import Link from 'components/shared/link/link';
import Card from 'components/shared/card/card';
import Spinner from 'components/shared/spinner/spinner';
import FilterBar from 'components/shared/filters/filter-bar/filter-bar.js';

import './album-list.css';

class AlbumList extends Component {
    constructor(props) {
        super(props);
        console.log("hey");
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
            pageSize: 10
        });

        this.setState({
            albums: response.albums,
            totalPages: response.totalPages,
            isLoading: false,
            initialLoad: false
        });
    }

    componentWillMount() {
        console.log(this);
        console.log("hey");
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
                        {this.state.albums.map((album, index) => {
                            return (<div key={index} className="gallery">
                                <Card

                                    thumbnail={album.thumbnail.smallUrl}
                                    href={'/new/gallery/' + album.id}
                                    title={album.titleEn}
                                    listItem={true}
                                />

                            </div>
                            )
                        })}
                    </div >
                    <div className='album-list-footer'>
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
