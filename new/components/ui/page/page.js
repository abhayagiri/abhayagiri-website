import React, { Component } from 'react';

import Spinner from '../../widgets/spinner/spinner';
import SubpageList from '../subpage/subpage-list/subpage-list';

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

    render() {
        let navPage = this.context.navPage;

        return navPage ? (
            <div className="page container">
                <div className="row">
                    <div className="col-2">
                        {this.state.isLoading ? <Spinner /> :
                            <SubpageList subpages={navPage.subpages} />
                        }
                    </div>
                    <div className='col-10'>
                        {React.cloneElement(this.props.children, {
                            subpages: navPage.subpages,
                            test: 'test'
                        })}
                    </div>
                </div>
            </div>
        ) : null;
    }
}

InfoPage.contextTypes = {
    navPage: React.PropTypes.object
}

export default InfoPage;