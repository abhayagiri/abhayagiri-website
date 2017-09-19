import React, { Component } from 'react';

import './subpage.css';

class Subpage extends Component {
    componentWillMount() {
        //this.getSubpage(this.props.route.path);
    }

    componentWillReceiveProps(nextProps) {
        //this.getSubpage(nextProps.route.path);
    }

    render() {
        console.log(this.props);

        let subpages = this.props.subpages;
        let subpage = subpages && subpages.filter(subpage => {
            console.log(subpage.slug, route.path);
            return subpage.slug === route.path;
        })[0];

        return (
            <div className="subpage">
                <legend></legend>

            </div>
        );
    }
}

export default Subpage;