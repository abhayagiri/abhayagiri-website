import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { getPathname, getPage } from 'components/shared/location';
import Link from 'components/shared/link/link';

export class Pagination extends Component {

    static propTypes = {
        onClick: PropTypes.func,
        t: PropTypes.func.isRequired,
        totalPages: PropTypes.number.isRequired
    }

    link(text, page, className) {
        className = 'page-item ' + (className ? className : '');
        return (
            <li className={className}>
                {page ?
                    <Link to={{
                        pathname: getPathname(),
                        query: this.getQuery(page)
                    }} className="page-link" onClick={this.props.onClick}
                    >{text}</Link> : <span className="page-link">{text}</span>}
            </li>
        );
    }

    getQuery(page) {
        let query = {};
        if (page && page > 1) {
            query.p = page;
        }
        return query;
    }

    render() {
        const { totalPages, t } = this.props;
        const page = getPage();

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

export default translate('common')(Pagination);
