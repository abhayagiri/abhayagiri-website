import React, { Component } from 'react';
import PropTypes from 'prop-types';

import './talks-card.css';

class TalksCard extends Component {
    render() {
        return (
            <div className="card">
                <img className="card-img-top" src={this.props.imageUrl} />
                <div className="card-block">
                    <h4 className="card-title">{this.props.title}</h4>
                    <div className="card-text">{this.props.children}</div>
                </div>
            </div>
        );
    }
}

TalksCard.propTypes = {
    imageUrl: PropTypes.string.isRequired,
    title: PropTypes.node.isRequired,
    children: PropTypes.node.isRequired
};

export default TalksCard;
