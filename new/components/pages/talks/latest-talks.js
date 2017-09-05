import React, { Component } from 'react';
import PropTypes from 'prop-types';

import BaseTalks from './base-talks';
import LatestTalksCard from './card/latest-talks-card';

class LatestTalks extends Component {

    render() {
        return (
            <BaseTalks
                basePath="latest"
                basePathMatch="latest"
                location={this.props.location}
                card={<LatestTalksCard />}
            />
        );
    }
}

export default LatestTalks;
