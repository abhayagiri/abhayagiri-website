import React, { Component } from 'react';
import { Link } from 'react-router';

import './breadcrumb.css';

class Breadcrumb extends Component {

    getPath(route) {
        let pathname = this.props.location.pathname;
        let path = route.path;
        path = pathname.split(path)[0] + path;
        return path;
    }

    parseTitle(title) {
        return title.split("-").map(segment => this.uppercase(segment)).join(" ");
    }

    uppercase(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    render() {
        // TEMP don't show for talks page
        const { pathname } = this.props.location;
        if (pathname && pathname.match('new/talks')) {
            return null;
        }

        let subpage = this.props.params.subpage;
        let routes = this.props.routes;

        if(subpage){
            routes.push({
                name: this.parseTitle(this.props.params.subpage)
            })
        }

        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        {this.props.routes.map((route, index) => {

                            let isActive = index == this.props.routes.length - 1;
                            let path = isActive ? '' : this.getPath(route);
                            let title = route.name;

                            return (
                                <li key={index} className={"breadcrumb-item " + (isActive ? 'active' : '')}>
                                    {!isActive ? <Link to={path}>{title}</Link> : <span>{title}</span>}
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