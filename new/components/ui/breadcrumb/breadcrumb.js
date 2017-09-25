import React, { Component } from 'react';
import {Link} from 'react-router';

import './breadcrumb.css';

class Breadcrumb extends Component {

    getPath(route) {
        let pathname = this.props.location.pathname;
        let path = route.path;
        path = pathname.split(path)[0] + path;
        return path;
    }

    replaceParams(){

    }

    render() {
        // TEMP don't show for talks page
        const { pathname } = this.props.location;
        if (pathname && pathname.match('new/talks')) {
            return null;
        }
        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        {this.props.routes.map((route, index) => {

                            let isActive = index == this.props.routes.length - 1;
                            let path = this.getPath(route);

                            return (
                                <li key={index} className={"breadcrumb-item " + (isActive ? 'active' : '')}>
                                    {!isActive ? <Link to={path}>{route.name}</Link> : <span>{route.name}</span>}
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