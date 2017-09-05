import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';

class Pagination extends Component {

    link(text, page, className) {
        className = 'page-item ' + className ? className : '';
        return (
            <li className={className}>
                {page ? <Link to={''} className="page-link">{text}</Link> : <span className="page-link">{text}</span>}
            </li>
        );
    }

    render() {
        let page = this.props.page,
            totalPages = this.props.totalPages;

        return (
            <nav>
                <ul className="pagination justify-content-center">
                    {/* Previous */}
                    {page <= 1 && this.link('Previous', null, 'disabled')}
                    {page > 1 && this.link('Previous', page - 1)}

                    {/* Previous Pages */}
                    {page >= 3 && this.link(page - 2, page - 2)}
                    {page >= 2 && this.link(page - 1, page - 1)}

                    {/* Current Page */}
                    {this.link(page, null, 'active')}

                    {/* Next Pages */}
                    {page <= (totalPages - 1) && this.link(page + 1, page + 1)}
                    {page <= (totalPages - 2) && this.link(page + 2, page + 2)}

                    {/* Next */}
                    {page >= totalPages && this.link('Next', null, 'disabled')}
                    {page < totalPages && this.link('Next', page + 1)}
                </ul>
            </nav>
        )
    }
}

export default Pagination;

