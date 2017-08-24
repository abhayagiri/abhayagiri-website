import React, { Component } from 'react';

import './breadcrumb.css';

class Breadcrumb extends Component {
    render() {
        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        {this.props.routes.map((route, index) => {
                            let isActive = index == this.props.routes.length - 1;
                            return (
                                <li key={index} className={"breadcrumb-item " + (isActive ? 'active' : '')}>
                                    {!isActive ? <a href="#">{route.name}</a> : <span>{route.name}</span>}
                                </li>
                            )
                        })}
                    </ol>
                </div>
            </div>
        );
    }
}

export default Breadcrumb;