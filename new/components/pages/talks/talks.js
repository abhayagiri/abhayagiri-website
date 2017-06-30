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
            <div className='categories'>

                {!this.state.category ?
                    <CategoryList categories={categories} onClick={this.setCategory.bind(this)} />
                    : ''}

                {this.state.category === 'Genres' ?
                    <CategoryList categories={genres} onClick={this.setGenre.bind(this)} />
                    : ''} 

                {/*{this.state.category && (this.state.teacher || this.state.genre) ? <div className="row">*/}
                    <div className="col-12"><TalkList talks={this.state.talks} /></div>
                {/*</div> : ''}*/}
            </div>
        );
    }
}

export default Audio;