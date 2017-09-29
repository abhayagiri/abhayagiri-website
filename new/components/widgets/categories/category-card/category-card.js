import React, { Component } from 'react';
import Link from 'components/widgets/link/link';

import './category-card.css';

class CategoryItem extends Component {
    constructor() {
        super();
        this.state = {
            showCategories: false
        }
    }

    // HACK just jump to top of talk-list (currently the only page that uses this)
    // when we click on a pagination link to avoid page jumping.
    jumpToNav() {
        let topOfNav;
        try {
            topOfNav = document.documentElement.scrollTop +
                document.getElementsByClassName('talk-list')[0].getBoundingClientRect().top
                - 20;
        } catch (e) {
            // Just in case...
            topOfNav = 450;
        }
        window.scrollTo(0, topOfNav);
    }

    isActive(title) {
        return this.context.pageName === title ? 'nav-link active' : 'nav-link';
    }

    toggleCategories() {
        this.setState({
            showCategories: !this.state.showCategories
        })
    }

    render() {
        let category = this.props.category;
        let subcategory = null;

        if (category.links) {
            subcategory = category.links.filter(link => link.active === true)[0];
        }
        return (
            <div>
                <div className="card hidden-sm-down">
                    <img className="card-img-top" src={category.imageUrl} />
                    <div className="card-block">
                        <h4 className="card-title">{category.title}</h4>
                    </div>
                    <ul className="list-group list-group-flush">
                        {category.links && category.links.map((link, index) => {
                            return (
                                <li key={index} className={"list-group-item " + (link.active ? 'active' : '')}>
                                    <Link onClick={this.jumpToNav} to={link.href}>{link.title}</Link>
                                </li>
                            );
                        })}
                    </ul>
                </div>

                <div className="card card-mobile hidden-md-up">
                    <div className="card-block">
                        <div className="row">
                            <div className="col-xs-3">
                                <img className="card-img-mobile" src={category.imageUrl} />
                            </div>
                            <div className="col-xs-9">
                                <h4 className="card-title">{category.title}</h4>
                                {subcategory && <div className="card-title">{subcategory.title}</div>}
                                {category.links && <div className={"dropdown " + (this.state.showCategories && 'show')}>
                                    <button
                                        onClick={this.toggleCategories.bind(this)}
                                        className="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Subcategories
                                    </button>
                                    <div className="dropdown-menu" >
                                        {category.links.map((link, index) => {
                                            return (
                                                <Link
                                                    key={index}
                                                    className={"dropdown-item " + (link.active && "active")}
                                                    onClick={this.jumpToNav}
                                                    to={link.href}>
                                                    {link.title}
                                                </Link>
                                            )
                                        })}
                                    </div>
                                </div>}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default CategoryItem;