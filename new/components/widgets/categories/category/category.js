import React, { Component } from 'react';
import Link from 'components/widgets/link/link';

import './category.css';

class CategoryItem extends Component {
    render() {
        let category = this.props.category;

        return (
            <Link to={category.href}>
                <div className="category card">
                    <img className="card-img-top" src={category.imageUrl} />
                    <div className="card-block">
                        <span className="card-title">{category.title}</span>
                    </div>
                </div>
            </Link>
        );
    }
}

export default CategoryItem;