import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Talk from '../talk/talk';
import Pagination from '../../../widgets/pagination/pagination';

class TalkList extends Component {

    render() {
        const
            { page, totalPages, searchText } = this.props,
            lng = this.props.i18n.language;

        return (
            <div className='talk-list'>
                {this.props.talks.map((talk, index) => {
                    return <div key={index}><Talk talk={talk} /><hr className='border' /></div>
                })}
                <Pagination page={page} totalPages={totalPages}/>
            </div>
        );
    }
}

TalkList.propTypes = {
    talks: PropTypes.array.isRequired,
    searchText: PropTypes.string.isRequired,
    page: PropTypes.number.isRequired,
    totalPages: PropTypes.number.isRequired
};

export default translate('talks')(TalkList);
