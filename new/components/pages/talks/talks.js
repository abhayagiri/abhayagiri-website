import React, { Component } from 'react';
import axios from 'axios';

import CategoryList from '../../widgets/category/category-list/category-list';
import TalkList from './talk-list/talk-list';
import TalksService from '../../../services/talks.service';

import './talks.css';

class Audio extends Component {

    constructor() {
        super();

        this.state = {
            talks: [],
            searchText: '',
            currentPage: 1,
            pageSize: 10,
            totalPages: 1,
            category: 'Dhamma Talks',
            teacher: false,
            genre: false
        }
    }

    componentWillMount() {
        let page = this.props.params.page || this.state.currentPage;
        this.getTalks({ page: page });
    }

    componentWillReceiveProps(nextProps) {
        let page = nextProps.params.page || this.state.currentPage;
        this.getTalks({ page: page });
    }

    async getTalks(filters) {
        let request = await TalksService.getTalks(filters);
        let talks = request.result;
        let currentPage = request.page;
        let totalPages = request.totalPages;
        let pageSize = request.pageSize;

        this.setState({
            talks: talks,
            currentPage: currentPage,
            totalPages: totalPages,
            pageSize: pageSize
        });
    }

    handleSearchChange(event) {
        this.setState({ searchText: event.target.value });
    }

    searchTalks() {
        console.log(this.state.searchText);
        this.getTalks({
            searchText: this.state.searchText
        });
    }

    setCategory(category) {
        this.setState({
            category: category
        });
        this.getTalks({
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
                <nav className="navbar navbar-toggleable-md navbar-light bg-faded" style={{ 'background-color': '#e3f2fd' }}>
                    <div className="container">
                        <a className="navbar-brand" href="#">Categories</a>
                        <button className="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span className="navbar-toggler-icon"></span>
                        </button>
                        <div className="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div className="navbar-nav mr-auto">
                                <a className="nav-item nav-link active category-link" onClick={this.setCategory.bind(this, 'Dhamma Talk')}>Dhamma Talks</a>
                                <a className="nav-item nav-link category-link" onClick={this.setCategory.bind(this, 'Chanting')}>Chanting</a>
                                <a className="nav-item nav-link category-link" onClick={this.setCategory.bind(this, 'Retreat')}>Retreats</a>
                                <a className="nav-item nav-link category-link" onClick={this.setCategory.bind(this, 'Collection')}>Collections</a>
                            </div>
                            <ul class="navbar-nav mr-auto"></ul>
                            <div className="form-inline my-2 my-lg-0 float-right">
                                <input className="form-control mr-sm-2" type="text" placeholder="Search" value={this.state.searchText} onChange={this.handleSearchChange.bind(this)} />
                                <button className="btn btn-outline-primary my-2 my-sm-0" onClick={this.searchTalks.bind(this)}>Search</button>
                            </div>
                        </div>
                    </div>
                </nav>

                <div className="content container">
                    {this.state.categorySelection && this.state.category === 'Teachers' &&
                        <CategoryList categories={categories} onClick={this.setCategory.bind(this)} />
                    }

                    {this.state.categorySelection && this.state.category === 'Genres' &&
                        <CategoryList categories={genres} onClick={this.setGenre.bind(this)} />
                    }

                    {!this.state.categorySelection &&
                        <div className="row">
                            {/*{this.state.category && (this.state.teacher || this.state.genre) ? <div className="row">*/}
                            <div className="col-3">
                                <div className="card" >
                                    <img className="card-img-top" src="http://www.littlebang.org/wp-content/uploads/2013/05/f-jayasaro-dhamma-tlak.jpg" alt="Card image cap" height="200px" />
                                    <div className="card-block">
                                        <h4 className="card-title">{this.state.category}</h4>
                                        <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-9"><TalkList pageSize={this.state.pageSize} totalPages={this.state.totalPages} currentPage={this.state.currentPage} talks={this.state.talks} /></div>
                            {/*</div> : ''}*/}
                        </div>}

                </div>


            </div>
        );
    }
}

export default Audio;