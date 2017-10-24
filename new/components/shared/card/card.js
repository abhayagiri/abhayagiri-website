import React, { Component } from 'react';
import Link from 'components/shared/link/link';

import './card.css';

class Card extends Component {
    render() {
        return (
            <div className={"card " + (this.props.listItem && "card-list-item")}>
                <img className="card-img-top" src={this.props.thumbnail} />
                <div className="card-block card-title">
                    <span >{this.props.title}</span>
                </div>
                {this.props.links && <ul className="list-group list-group-flush">
                    {this.props.links.map((link, index) => {
                        return (
                            <li key={index} className={"list-group-item " + (link.active ? 'active' : '')}>
                                <Link to={link.href}>{link.title}</Link>
                            </li>
                        );
                    })}
                </ul>}
            </div>
        );
    }
}

export default Card;