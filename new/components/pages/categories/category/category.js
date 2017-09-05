import React, { Component } from 'react';
import { Link } from 'react-router';

import './category.css';

class CategoryItem extends Component {
    render() {
        let category = this.props.category,
            background = 'url(\'http://www.abhayagiri.org/' + category.imagePath + '\')',
            image = 'http://www.abhayagiri.org/' + category.imagePath;
        console.log();
        return (
            <Link to={category.href}>
                <div className="category card">
                    <img className="card-img-top" src={image} />
                    <div className="card-block">
                        <span className="card-title">{category.title}</span>
                    </div>
                </div>
            </Link>
        );
    }
}

export default CategoryItem;