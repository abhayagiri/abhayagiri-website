import React, { Component } from 'react';
import PropTypes from 'prop-types';
import CategoryToolbar from './category-toolbar/category-toolbar';

class TalksPage extends Component {

    constructor() {
        super();

        this.state = {
            category: '',
        };
    }

    componentWillMount() {
        let category = this.getCategory(this.props);
    }

    getCategory(){
        return 'blah'
    }

    render() {
        return (
            <div>
                <div className="categories container">
                    {React.cloneElement(this.props.children, {
                    })}
                </div>
            </div>
        )
    }
}

export default TalksPage;
