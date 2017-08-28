import React, { Component } from 'react';

import './talks.css';

class TalksContainer extends Component {
    render() {
        return (
            <div className="talks-container">{this.props.children}</div>
        );
    }
}

export default TalksContainer;
