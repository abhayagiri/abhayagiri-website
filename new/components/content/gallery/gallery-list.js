import React, { Component } from 'react';
import GalleryService from 'services/gallery.service';
import Link from 'components/shared/link/link';

import './gallery.css';

class Gallery extends Component {
    constructor() {
        super();
        this.state = {
            galleries: []
        }
    }

    async getGalleries() {
        let galleries = await GalleryService.getGalleries();
        this.setState({
            galleries: galleries
        });
    }

    componentWillMount() {
        this.getGalleries();
    }

    render() {
        return (
            <div className='gallery-list container content'>
                {this.state.galleries.map(gallery => {
                    return (<div className="gallery">
                        <div className="card card-list-item">
                            <img className="card-img-top" src={gallery.thumbnail} alt="Card image cap" />
                            <div className="card-block">
                                <Link to={'./' + gallery.id}><h4 className="card-title">
                                    {gallery.title}
                                </h4></Link>
                            </div>
                        </div>
                    </div>
                    )
                })}
            </div >
        );
    }
}

export default Gallery;
