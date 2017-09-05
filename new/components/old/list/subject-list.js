import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TileList from './tile-list.js';
import { tp } from '../../../../i18n';
import { subjectPath } from '../util';

class SubjectList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return <TileList list={this.props.subjects.map((subject) => {
            return {
                imagePath: subject.imageUrl,
                path: subjectPath(this.props.subjectGroup, subject, { lng }),
                title: tp(subject, 'title')
            };
        })} />;
    }
}

SubjectList.propTypes = {
    subjectGroup: PropTypes.object.isRequired,
    subjects: PropTypes.array.isRequired
};

export default translate('talks')(SubjectList);
