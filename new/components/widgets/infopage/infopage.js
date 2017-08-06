import React, { Component } from 'react';

import Spinner from '../spinner/spinner';
import SubpageList from '../subpage/subpage-list/subpage-list';

import './infopage.css';

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
        await this.props.page.getSubpages();
        this.setState({
            isLoading: false
        });
    }

    render() {
        return this.props.page ? (
            <div className="infopage">
                <div className="row">
                    <div className="col-3">
                        {this.state.isLoading ? <Spinner /> :
                            <SubpageList subpages={this.props.page.subpages}/>
                        }
                    </div>
                    <div className='col-9'>
                        {this.props.children}
                    </div>
                </div>
            </div>
        ) : null;
    }
}

export default InfoPage;