import React, { Component } from 'react';
import { withRouter } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import './talks-search.css';

class SearchTalks extends Component {

    constructor(props) {
        super(props);
        this.state = { value: '' };
        this.handleChange = this.handleChange.bind(this);
        this.handleKeyPress = this.handleKeyPress.bind(this);
        this.searchTalks = this.searchTalks.bind(this);
    }

    handleChange(event) {
        this.setState({ value: event.target.value });
    }

    handleKeyPress(event) {
        if (event.key === 'Enter') {
            this.searchTalks();
        }
    }

    searchTalks() {
        const { value } = this.state;
        if (!value) {
            return;
        }
        
        const newLocation = '';
        this.props.router.push(newLocation);
    }

    render() {
        const { t } = this.props;
        return (
            <div className="form-inline my-2 my-lg-0 float-right">
                <input
                    className="form-control mr-sm-2"
                    type="text"
                    placeholder={t('search')}
                    value={this.state.value}
                    onChange={this.handleChange}
                    onKeyPress={this.handleKeyPress} />
                <button
                    className="btn btn-outline-primary my-2 my-sm-0"
                    onClick={this.searchTalks}>{t('search')}</button>
            </div>
        );
    }
}

SearchTalks.propTypes = {
    basePath: PropTypes.string.isRequired
}

export default withRouter(translate('talks')(SearchTalks));
