import React, { Component } from 'react';
import { RelativeLink } from 'react-router-relative-links'

import './category-card.css';

class CategoryItem extends Component {

    // HACK just jump to top of talk-list (currently the only page that uses this)
    // when we click on a pagination link to avoid page jumping.
    jumpToNav() {
        console.log('jump!');
        let topOfNav;
        try {
            topOfNav = document.documentElement.scrollTop +
                document.getElementsByClassName('talk-list')[0].getBoundingClientRect().top
                -20;
        } catch (e) {
            // Just in case...
            topOfNav = 450;
        }
        window.scrollTo(0, topOfNav);
    }

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
                                <RelativeLink onClick={this.jumpToNav} to={link.href}>{link.title}</RelativeLink>
                            </li>
                        );
                    })}
                </ul>}
            </div>
        );
    }
}

export default CategoryItem;