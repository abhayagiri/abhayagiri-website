import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import Link from 'components/shared/link/link';

export class Pagination extends Component {

    static propTypes = {
        location: PropTypes.object.isRequired,
        page: PropTypes.number.isRequired,
        searchText: PropTypes.string.isRequired,
        t: PropTypes.func.isRequired,
        totalPages: PropTypes.number.isRequired
    }

    link(text, page, className) {
        className = 'page-item ' + (className ? className : '');
        return (
            <li className={className}>
                {page ?
                    <Link to={{
                        pathname: this.getPathname(),
                        query: this.getQuery(page)
                    }} className="page-link">{text}</Link> : <span className="page-link">{text}</span>}
            </li>
        );
    }

    getPathname() {
        return this.props.location.pathname;
    }

    getQuery(page) {
        let query = {};
        if (page && page > 1) {
            query.p = page;            
        }
        if (this.props.searchText) {
            query.q = this.props.searchText;
        }
        return query;
    }

    render() {
        const { page, totalPages, t } = this.props;

        return (
            <nav>
                <ul className="pagination justify-content-center">
                    {/* Previous */}
                    {page <= 1 && this.link(t('previous'), null, 'disabled')}
                    {page > 1 && this.link(t('previous'), page - 1)}

                    {/* Previous Pages */}
                    {page >= 6 && this.link(page - 5, page - 5)}
                    {page >= 5 && this.link(page - 4, page - 4)}
                    {page >= 4 && this.link(page - 3, page - 3)}
                    {page >= 3 && this.link(page - 2, page - 2)}
                    {page >= 2 && this.link(page - 1, page - 1)}

                    {/* Current Page */}
                    {this.link(page, null, 'active')}

                    {/* Next Pages */}
                    {page <= (totalPages - 1) && this.link(page + 1, page + 1)}
                    {page <= (totalPages - 2) && this.link(page + 2, page + 2)}
                    {page <= (totalPages - 3) && this.link(page + 3, page + 3)}
                    {page <= (totalPages - 4) && this.link(page + 4, page + 4)}
                    {page <= (totalPages - 5) && this.link(page + 5, page + 5)}

                    {/* Next */}
                    {page >= totalPages && this.link(t('next'), null, 'disabled')}
                    {page < totalPages && this.link(t('next'), page + 1)}
                </ul>
            </nav>
        )
    }
}

export default translate('common')(
    withGlobals(Pagination, ['location', 'page', 'searchText'])
);
