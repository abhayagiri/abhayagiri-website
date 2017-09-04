import React, { Component } from 'react';
import { Link } from 'react-router';
import PropTypes from 'prop-types';

import './tile-list.css';

class TileList extends Component {
    render() {
        return (
            <div className="tile-list card-group">
                {this.props.list.map((item, index) => {
                    return (
                        <div className="card" key={index}>
                            <Link to={item.path}>
                                <img className="card-img-top" src={item.imagePath} />
                            </Link>
                            <div className="card-block">
                                <p className="card-text">
                                    <Link to={item.path}>{item.title}</Link>
                                </p>
                            </div>
                        </div>
                    );
                })}
            </div>
        );
    }
}

TileList.propTypes = {
    list: PropTypes.array.isRequired
};

export default TileList;
