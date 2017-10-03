import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import FilterBar from '../../widgets/filters/filter-bar/filter-bar.js';

import './talks.css';



class TalksPage extends Component {

    render() {
        const links = [
            {
                href: '/talks/types/2-dhamma-talks',
                title: 'Latest'
            },
            {
                href: '/talks/teachers',
                title: 'Teachers'
            },
            {
                href: '/talks/subjects',
                title: 'Subjects'
            },
            {
                href: '/talks/collections',
                title: 'Collections'
            }
        ];

        return (
            <div className='talks'>
                <FilterBar links={links}/>
                <div className="talks container">
                    {React.cloneElement(this.props.children, {
                        params: this.props.params
                    })}
                </div>
            </div>
        )
    }
}

TalksPage.childContextTypes = {
    pageSize: React.PropTypes.number
}

const TalksWithTranslate = translate('talks')(TalksPage);

export default TalksWithTranslate;