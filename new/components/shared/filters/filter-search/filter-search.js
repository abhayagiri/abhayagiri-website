import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';
import { withRouter } from 'react-router';

import { localizePathname } from 'components/shared/link/link';
import Link from 'components/shared/link/link';
import './filter-search.css';

export class Search extends Component {

    static propTypes = {
        searchTo: PropTypes.oneOfType([
            PropTypes.string,
            PropTypes.func
        ]).isRequired,
        router: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            value: '',
            isLoading: false
        };
        this.handleChange = this.handleChange.bind(this);
        this.handleKeyPress = this.handleKeyPress.bind(this);
        this.search = this.search.bind(this);
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
        const searchText = this.state.value;
        let to;
        if (typeof this.props.searchTo === 'function') {
            to = this.props.searchTo(searchText);
        } else {
            to = {
                pathname: localizePathname(this.props.searchTo) +
                    encodeURIComponent(searchText)
            };
        }

        this.setState({
            isLoading: true
        });

        this.props.router.push(to);

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

                <button
                    disabled={this.state.isLoading}
                    className="btn btn-outline-primary my-2 my-sm-0"
                    onClick={this.search}>
                    <i className="fa fa-search" aria-hidden="true" />
                </button>
            </div>
        );
    }
}

export default withRouter(translate('header')(Search));
