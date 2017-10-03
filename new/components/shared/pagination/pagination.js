import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';
import Link from 'components/shared/link/link';
import QueryService from '../../../services/query.service';

class Pagination extends Component {

    // HACK just jump to top of talk-list (currently the only page that uses this)
    // when we click on a pagination link to avoid page jumping.
    jumpToNav() {
        console.log('jump!');
        let topOfNav;
        try {
            topOfNav = document.documentElement.scrollTop +
                document.getElementsByClassName('talk-list')[0].getBoundingClientRect().top
                -20;
        } catch (e) {
            // Just in case...
            topOfNav = 450;
        }
        window.scrollTo(0, topOfNav);
    }

    link(text, page, className) {
        className = 'page-item ' + (className ? className : '');
        return (
            <li className={className}>
                {page ?
                    <Link onClick={this.jumpToNav} to={{
                        pathname: this.getPathname(),
                        query: this.getQuery(page)
                    }} className="page-link">{text}</Link> : <span className="page-link">{text}</span>}
            </li>
        );
    }

    getPathname() {
        return this.context.location.pathname;
    }

    getQuery(page) {
        return {
            p: page,
            q: this.context.searchText
        };
    }

    render() {
        let page = this.context.page,
            { totalPages, t } = this.props;

        return (
            <nav>
                <ul className="pagination justify-content-center">
                    {/* Previous */}
                    {page <= 1 && this.link(t('previous'), null, 'disabled')}
                    {page > 1 && this.link(t('previous'), page - 1)}

                    {/* Previous Pages */}
                    {page >= 3 && this.link(page - 2, page - 2)}
                    {page >= 2 && this.link(page - 1, page - 1)}

                    {/* Current Page */}
                    {this.link(page, null, 'active')}

                    {/* Next Pages */}
                    {page <= (totalPages - 1) && this.link(page + 1, page + 1)}
                    {page <= (totalPages - 2) && this.link(page + 2, page + 2)}

                    {/* Next */}
                    {page >= totalPages && this.link(t('next'), null, 'disabled')}
                    {page < totalPages && this.link(t('next'), page + 1)}
                </ul>
            </nav>
        )
    }
}

Pagination.contextTypes = {
    location: React.PropTypes.object,
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default translate('common')(Pagination);
