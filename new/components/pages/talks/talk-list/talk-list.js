import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Talk from '../talk/talk';

class TalksList extends Component {

    render() {
        const { page, totalPages } = this.props;

        const getPath = (n) => {
            return this.props.scope.getPath({
                lng: this.props.i18n.language,
                searchText: this.props.searchText,
                page: n
            });
        }

        const link = (text, n, extraClass) => {
            const listItemClass = 'page-item' + (extraClass ? ' ' + extraClass : '');
            return (
                <li className={listItemClass}>{
                    n ? <Link className="page-link" to={getPath(n)}>{text}</Link>
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

TalksList.propTypes = {
    talks: PropTypes.array.isRequired,
    totalPages: PropTypes.number.isRequired,
    page: PropTypes.number.isRequired
};

const TalksListWithWrapper = translate('talks')(TalksList);

export default TalksListWithWrapper;
