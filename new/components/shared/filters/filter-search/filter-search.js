import React, { Component } from 'react';
import { withRouter } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';
import { localizePathname } from 'components/shared/link/link';
import Link from 'components/shared/link/link';

import './filter-search.css';

class Search extends Component {

    constructor(props) {
        super(props);
        this.state = {
            value: '',
            isLoading: false
        };
        this.handleChange = this.handleChange.bind(this);
        this.handleKeyPress = this.handleKeyPress.bind(this);
        this.search = this.search.bind(this);
    }

    getPathname() {
        return this.context.location.pathname;
    }

    getQuery() {
        return {
            p: this.context.page,
            q: this.state.value
        };
    }

    handleChange(event) {
        this.setState({ value: event.target.value });
    }

    handleKeyPress(event) {
        if (event.key === 'Enter') {
            this.search();
        }
    }

    search() {
        const { value } = this.state;
        const path = this.props.href + encodeURIComponent(value);

        if (!value) {
            return;
        }

        this.setState({
            isLoading: true
        });

        this.props.router.push({
            pathname: localizePathname(path)
        });

        // Fake ajax timeout
        setTimeout(() => {
            this.setState({
                isLoading: false
            })
        }, 500);
    }

    render() {
        const { t } = this.props;
        let extraClassName = '';
        extraClassName += this.state.value ? ' with-data' : ' without-data';
        extraClassName += this.state.isLoading ? ' loading' : ' not-loading';


        return (
            <div className={"search form-inline my-2 my-lg-0 float-right" + extraClassName}>
                <div className="loader" />
                <input
                    disabled={this.state.isLoading}
                    className={"form-control mr-sm-2"}
                    type="text"
                    placeholder={t('search')}
                    value={this.state.value}
                    onChange={this.handleChange}
                    onKeyPress={this.handleKeyPress} />

                {this.props.href ? <button
                    disabled={this.state.isLoading}
                    className="btn btn-outline-primary my-2 my-sm-0"
                    onClick={this.search}>
                    <i className="fa fa-search" aria-hidden="true"></i>
                </button> : <Link to={{
                    pathname: this.getPathname(),
                    query: this.getQuery()
                }}><button
                    disabled={this.state.isLoading}
                    className="btn btn-outline-primary my-2 my-sm-0">
                            <i className="fa fa-search" aria-hidden="true"></i>
                        </button></Link>}

            </div>
        );
    }
}
Search.contextTypes = {
    location: React.PropTypes.object,
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default withRouter(translate('talks')(Search));
