import React, { Component } from 'react';
import { RelativeLink } from 'react-router-relative-links'

import './category-card.css';

class CategoryItem extends Component {
    render() {
        let category = this.props.category;

        return (
            <div className="card fixed">
                <img className="card-img-top" src={category.imageUrl} />
                <div className="card-block">
                    <h4 className="card-title">{category.title}</h4>
                </div>
                {category.links && <ul className="list-group list-group-flush">
                    {category.links.map((link, index) => {
                        return (
                            <li key={index} className={"list-group-item " + (link.active ? 'active' : '')}>
                                <RelativeLink to={link.href}>{link.title}</RelativeLink>
                            </li>
                        );
                    })}
                </ul>}
            </div>
        );
    }
}

export default CategoryItem;