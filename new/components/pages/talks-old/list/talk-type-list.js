import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TileList from './tile-list.js';
import { tp } from '../../../../i18n';
import { talkTypeTalksPath } from '../util';

class TalkTypeList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return <TileList list={this.props.talkTypes.map((talkType) => {
            return {
                imagePath: talkType.imageUrl,
                path: talkTypeTalksPath(talkType, { lng }),
                title: tp(talkType, 'title')
            };
        })} />;
    }
}

TalkTypeList.propTypes = {
    talkTypes: PropTypes.array.isRequired
};

export default translate('talks')(TalkTypeList);
