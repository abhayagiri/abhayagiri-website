import React, { Component } from 'react';

import './breadcrumb.css';

class Breadcrumb extends Component {
    render() {
        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        <li className="breadcrumb-item"><a href="#">Home</a></li>
                        <li className="breadcrumb-item"><a href="#">Library</a></li>
                        <li className="breadcrumb-item active">Data</li>
                    </ol>
                </div>
            </div>
        );
    }
}

export default Breadcrumb;