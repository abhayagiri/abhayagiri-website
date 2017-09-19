import React, { Component } from 'react';
import Spinner from '../../widgets/spinner/spinner';

import './page.css';

class InfoPage extends Component {

    constructor() {
        super();
        this.state = {
            isLoading: false
        }
    }

    componentWillMount() {
        this.getSubpages();
    }

    componentWillReceiveProps(nextProps) {
        this.getSubpages();
    }

    async getSubpages() {
        this.setState({
            isLoading: true
        });

        await this.context.navPage.getSubpages();

        this.setState({
            isLoading: false
        });
    }

    getChildContext() {
        return {
            subpages: this.context.navPage.subpages,
        }
    }

    render() {
        let navPage = this.context.navPage;

        return !this.state.isLoading ? (
            <div className="page container">
                {this.props.children}
            </div>
        ) : <Spinner/>;
    }
}

InfoPage.contextTypes = {
    navPage: React.PropTypes.object
}

InfoPage.childContextTypes = {
    subpages: React.PropTypes.Array,
}

export default InfoPage;