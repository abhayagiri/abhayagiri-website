import React, { Component } from 'react';
import Link from 'components/shared/link/link';

import './category.css';

class CategoryItem extends Component {
    render() {
        let category = this.props.category;

        return (
            <Link to={category.href}>
                <div className="category card card-list-item">
                    <img className="card-img-top" src={category.imageUrl} />
                    <div className="card-block card-title">
                        <span >{category.title}</span>
                    </div>
                </div>
            </Link>
        );
    }
}

export default CategoryItem;