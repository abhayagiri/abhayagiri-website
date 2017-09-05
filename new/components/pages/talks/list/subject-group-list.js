import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TileList from './tile-list.js';
import { tp } from '../../../../i18n';
import { subjectGroupPath } from '../util';

class SubjectGroupList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return <TileList list={this.props.subjectGroups.map((subjectGroup) => {
            return {
                imagePath: subjectGroup.imageUrl,
                path: subjectGroupPath(subjectGroup, { lng }),
                title: tp(subjectGroup, 'title')
            };
        })} />;
    }
}

SubjectGroupList.propTypes = {
    subjectGroups: PropTypes.array.isRequired
};

export default translate('talks')(SubjectGroupList);
