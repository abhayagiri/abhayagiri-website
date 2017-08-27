import React, { Component } from 'react';
import { Link, withRouter } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import getTalksScope from './scope.js';
import TalksHeader from './talks-header/talks-header.js';
import TalksMain from './talks-main/talks-main.js';
import Spinner from '../../widgets/spinner/spinner';

import './talks.css';

class Talks extends Component {

    constructor() {
        super();
        this.state = {
            searchText: ''
        }
    }

    handleSearchChange(event) {
        this.setState({ searchText: event.target.value });
    }

    handleSearchKeyPress(event) {
        if (event.key === 'Enter') {
            this.searchTalks()
        }
    }

    searchTalks() {
        const path = this.props.scope.getPath({
            lng: this.props.i18n.language,
            searchText: this.state.searchText
        });
        this.props.router.push(path);
    }

    renderSearch() {
        if (this.props.scope.name === 'authors') {
            return null;
        } else {
            const { t } = this.props;
            return (
                <div className="form-inline my-2 my-lg-0 float-right">
                    <input className="form-control mr-sm-2" type="text" placeholder={t('search')}
                        value={this.state.searchText}
                        onChange={this.handleSearchChange.bind(this)}
                        onKeyPress={this.handleSearchKeyPress.bind(this)} />
                    <button className="btn btn-outline-primary my-2 my-sm-0" onClick={this.searchTalks.bind(this)}>{t('search')}</button>
                </div>
            );
        }
    }

    render() {
        return (
            <div className='categories '>
                <nav className={"navbar navbar-toggleable-sm navbar-light bg-faded"} style={{ 'backgroundColor': '#e3f2fd' }}>
                    <div className="container">
                        <div className={"navbar-collapse"} id="navbarNavAltMarkup">
                            <TalksHeader scope={this.props.scope} />
                            {this.renderSearch()}
                        </div>
                    </div>
                </nav>

                <TalksMain
                    scope={this.props.scope}
                    searchText={this.props.searchText}
                    page={this.props.page}
                />
            </div>
        );
    }
}

Talks.defaultProps = {
    searchText: '',
    page: 1
}

Talks.propTypes = {
    scope: PropTypes.object.isRequired,
    searchText: PropTypes.string.isRequired,
    page: PropTypes.number.isRequired,
    router: PropTypes.shape({
        push: PropTypes.func.isRequired
    }).isRequired
};

const TalksWithWrappers = withRouter(translate('talks')(Talks));

// Container class that assigns scope, searchText and page props from the path.
class ProppedTalks extends Component {

    render() {
        const
            scope = getTalksScope(this.props.route.scopeName, this.props.params),
            location = this.props.location,
            searchText = location.query.q || '',
            page = parseInt(location.query.p) || 1;
        return (
            <TalksWithWrappers scope={scope} searchText={searchText} page={page} />
        );
    }
}

export default ProppedTalks;
