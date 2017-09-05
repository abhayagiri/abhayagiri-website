import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { talksPath } from '../util';
import Talk from '../talk/talk';

class TalkList extends Component {

    render() {
        const
            { basePath, page, totalPages, searchText } = this.props,
            lng = this.props.i18n.language;

        function link(text, page, extraClass) {
            const listItemClass = 'page-item' + (extraClass ? ' ' + extraClass : '');
            return (
                <li className={listItemClass}>{
                    page ? <Link
                        to={talksPath(basePath, { lng, searchText, page })}
                        className="page-link">{text}</Link>
                    : <span className="page-link">{text}</span>
                }</li>
            );
        }

        return (
            <div className='talk-list'>
                {this.props.talks.map((talk,index) => {
                    return <div key={index}><Talk talk={talk} /><hr className='border' /></div>
                })}
                <nav>
                    <ul className="pagination justify-content-center">
                        {/* Previous */}
                        {page <= 1 && link('Previous', null, 'disabled')}
                        {page > 1 && link('Previous', page - 1)}

                        {/* Pages */}
                        {page >= 3 && link(page - 2, page - 2)}
                        {page >= 2 && link(page - 1, page - 1)}
                        {link(page, null, 'active')}
                        {page <= (totalPages - 1) && link(page + 1, page + 1)}
                        {page <= (totalPages - 2) && link(page + 2, page + 2)}

                        {/* Next */}
                        {page >= totalPages && link('Next', null, 'disabled')}
                        {page < totalPages && link('Next', page + 1)}
                    </ul>
                </nav>
            </div>
        );
    }
}

TalkList.propTypes = {
    basePath: PropTypes.string.isRequired,
    talks: PropTypes.array.isRequired,
    searchText: PropTypes.string.isRequired,
    page: PropTypes.number.isRequired,
    totalPages: PropTypes.number.isRequired
};

export default translate('talks')(TalkList);
