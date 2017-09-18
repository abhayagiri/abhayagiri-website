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
        return (
            <div className="subpage">
                <legend></legend>

            </div>
        );
    }
}

export default Subpage;