import React, { Component } from 'react';
import PropTypes from 'prop-types';

import Spinner from '../../../shared/spinner/spinner';
import TalksHeader from '../talks-header/talks-header';

import './talks-layout.css';

class TalksLayout extends Component {

    render() {
        return (
            <div>
                <nav className={"talks-header navbar navbar-toggleable-sm navbar-light bg-faded"}>
                    <div className="container">
                        <div className={"navbar-collapse"} id="navbarNavAltMarkup">
                            <TalksHeader basePathMatch={this.props.basePathMatch} />
                            {this.props.search ? this.props.search : null}
                        </div>
                    </div>
                </nav>
                <div className="talks-details content container">
                    <div className="row">
                        <div className="col-lg-3 hidden-md-down">
                            {this.props.card ? this.props.card : <Spinner />}
                        </div>
                        <div className="col-lg-9 col-md-12">
                            {this.props.details ? this.props.details : <Spinner />}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

TalksLayout.propTypes = {
    basePathMatch: PropTypes.string.isRequired,
    search: PropTypes.object,
    card: PropTypes.object,
    details: PropTypes.object
};

export default TalksLayout;
