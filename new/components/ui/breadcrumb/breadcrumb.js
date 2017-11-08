import React, { Component } from 'react';
import Link from 'components/shared/link/link';
import EventEmitter from 'services/emitter.service';


import './breadcrumb.css';

class Breadcrumb extends Component {
    constructor(){
        super();

        this.state = {
            routes: []
        }
    }

    componentWillMount() {
        this.getRoutes(this.props.routes);
    }

    componentWillReceiveProps(nextProps){
        this.getRoutes(nextProps.routes);
    }

    componentDidMount() {
        EventEmitter.on('breadcrumb', this.updateTitle.bind(this));
        console.log("Hey!");
    }

    getPath(route) {
        let pathname = this.props.location.pathname;
        let path = route.path;
        path = (path === '/new') ? '/' : pathname.split(path)[0] + path;
        return path;
    }

    parseTitle(title) {
        console.log(title);
        return title.split("-").map(segment => this.uppercase(segment)).join(" ");
    }

    getRoutes(routes) {
        let subpage = this.props.params.subpage;
        let isActive = false;

        routes = routes.map((route, index) => {
            isActive = (index === this.props.routes.length - 1);
            
            return {
                isActive: isActive,
                path: isActive ? '' : this.getPath(route),
                subpage: subpage,
                title: route.name || (subpage ? this.parseTitle(subpage) : "") 
            }
        });

        this.setState({
            routes: routes
        })
    }

    updateTitle(title){
        let routes = this.state.routes;
        routes[routes.length - 1].title = title;    
        this.setState({
            routes: routes
        });
    }

    uppercase(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    render() {
        // TEMP don't show for talks page
        const { pathname } = this.props.location;

        if (pathname && pathname.match('new/(th/)?talks')) {
            return null;
        }

        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        {this.state.routes.map((route, index) => {
                            return (
                                <li key={index} className={"breadcrumb-item " + (route.isActive ? 'active' : '')}>
                                    {!route.isActive ?
                                        <Link to={route.path}>{route.title}</Link> :
                                        <span>{route.title}</span>}
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