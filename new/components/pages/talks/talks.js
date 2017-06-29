import React, { Component } from 'react';
import axios from 'axios';

import CategoryList from '../../widgets/category/category-list/category-list';
import TalkList from './talk-list/talk-list';

class Audio extends Component {

    constructor(){
        super();

        this.state = {
            talks:[]
        }
    }

    componentWillMount(){
        this.getTalks();
    }

    async getTalks(){
        let result = await axios.get('/data/audio.json'),
            talks = result.data.audio;

        this.setState({
            talks: talks
        })
    }

    render() {

        let categories = [
            {
                title:'Latest',
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

        return (
            <div className='categories'>
                {/*<CategoryList categories={categories}/>*/}
                <div className="row">
                    <div className="col-12"><TalkList talks={this.state.talks}/></div>
                </div>
            </div>
        );
    }
}

export default Audio;