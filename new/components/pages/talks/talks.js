import React, { Component } from 'react';
import axios from 'axios';

import CategoryList from '../../widgets/category/category-list/category-list';
import TalkList from './talk-list/talk-list';
import TalksService from '../../../services/talks.service';

class Audio extends Component {

    constructor() {
        super();

        this.state = {
            talks: [],
            category: false,
            teacher: false,
            genre: false
        }
    }

    componentWillMount() {
        this.getTalks();
    }

    async getTalks() {
        let talks = await TalksService.getTalks();
        console.log(talks);
        this.setState({
            talks: talks
        })
    }

    setCategory(category) {
        console.log(category);
        this.setState({
            category: category
        });
    }

    setGenre(genre) {
        this.setState({
            genre: genre
        });
    }

    render() {

        let categories = [
            {
                title: 'Latest',
                image: ''
            },
            {
                title: 'Teachers',
                image: ''
            },
            {
                title: 'Genres',
                image: ''
            }
        ]

        let genres = [
            {
                title: 'Metta',
                image: ''
            },
            {
                title: 'Nibbana',
                image: ''
            },
            {
                title: 'Anicca',
                image: ''
            },
            {
                title: 'Asubha',
                image: ''
            },
            {
                title: 'Dukkha',
                image: ''
            },
            {
                title: 'Uppeka',
                image: ''
            }
        ]

        return (
            <div className='categories '>
                <nav className="navbar navbar-toggleable-md navbar-light bg-faded">
                    <div className="container">
                        <button className="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span className="navbar-toggler-icon"></span>
                        </button>
                        <div className="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div className="navbar-nav">
                                <a className="nav-item nav-link active" href="#" onClick={this.setCategory.bind(this,'Latest')}>Latest</a>
                                <a className="nav-item nav-link" href="#" onClick={this.setCategory.bind(this,'Teachers')}>Teachers</a>
                                <a className="nav-item nav-link" href="#" onClick={this.setCategory.bind(this,'Genres')}>Genres</a>
                                <a className="nav-item nav-link" href="#" onClick={this.setCategory.bind(this,'Retreats')}>Retreats</a>
                            </div>
                        </div>
                    </div>
                </nav>
                <div className="content container">
                    
                    {!this.state.category === 'Teachers' ?
                        <CategoryList categories={categories} onClick={this.setCategory.bind(this)} />
                        : ''}

                    {this.state.category === 'Genres' ?
                        <CategoryList categories={genres} onClick={this.setGenre.bind(this)} />
                        : ''}

                    <div className="row">
                        {/*{this.state.category && (this.state.teacher || this.state.genre) ? <div className="row">*/}
                        <div className="col-3">
                            <div className="card" >
                                <img className="card-img-top" src="http://www.littlebang.org/wp-content/uploads/2013/05/f-jayasaro-dhamma-tlak.jpg" alt="Card image cap" height="200px" />
                                <div className="card-block">
                                    <h4 className="card-title">Latest Talks</h4>
                                    <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-9"><TalkList talks={this.state.talks} /></div>
                        {/*</div> : ''}*/}
                    </div>
                </div>
            </div>
        );
    }
}

export default Audio;