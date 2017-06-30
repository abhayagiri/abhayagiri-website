import React, { Component } from 'react';

import './category.css';

class CategoryItem extends Component {
    render() {
        return (
            <div className='category' onClick={this.props.onClick.bind(this.props.category.title)}>
               {this.props.category.title}
            </div>
        );
    }
}

export default CategoryItem;