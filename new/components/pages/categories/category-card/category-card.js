import React, { Component } from 'react';
import { Link } from 'react-router';

import './category-card.css';

class CategoryItem extends Component {
    render() {
        let category = this.props.category,
            image = 'http://www.abhayagiri.org/' + category.imagePath;

        return (
            <div className="card">
                <img className="card-img-top" src={image} />
                <div className="card-block">
                    <span className="card-title">{category.title}</span>
                </div>
            </div>
        );
    }
}

export default CategoryItem;