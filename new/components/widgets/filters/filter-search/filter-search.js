import React, { Component } from 'react';
import { withRouter } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';
import { setLanguage } from '../../../../utils/language';

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
        const path = setLanguage('/new/talks/search/' + value);
        console.log(path);
        if (!value) {
            return;
        }

        this.setState({
            isLoading: true
        });

        this.props.router.push({
            pathname: path
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
                <button
                    disabled={this.state.isLoading}
                    className="btn btn-outline-primary my-2 my-sm-0"
                    onClick={this.search}>
                    <i className="fa fa-search" aria-hidden="true"></i>
                </button>
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
