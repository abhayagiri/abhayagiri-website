import React, { Component } from 'react';
import Link from 'components/widgets/link/link';

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

        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        {this.props.routes.map((route, index) => {

                            let isActive = index == this.props.routes.length - 1;
                            let path = isActive ? '' : this.getPath(route);
                            let subpage = this.props.params.subpage;
                            let title = route.name || this.parseTitle(subpage);

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