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

    getSubpage(subpages) {
        let routes = this.props.routes;
        let route = routes[routes.length - 1];
        return subpages && subpages.filter(subpage => {
            return subpage.subpath === route.path;
        })[0];
    }

    getChildContext() {
        let subpages = this.context.navPage.subpages;
        let subpage = this.getSubpage(subpages);

        return {
            subpage: subpage,
        }
    }

    render() {
        let navPage = this.context.navPage;
        let subpages = this.context.navPage.subpages;
        let subpage = this.getSubpage(subpages);

        return !this.state.isLoading ? (
            <div className="page container">
                <div className="row">
                    <div className="col-lg-2">
                        <SubpageList active={subpage} subpages={subpages} />
                    </div>
                    <div className='col-lg-10'>
                        {this.props.children}
                    </div>
                </div>

            </div>
        ) : <Spinner />;
    }
}

InfoPage.contextTypes = {
    navPage: React.PropTypes.object
}

InfoPage.childContextTypes = {
    subpage: React.PropTypes.object,
}

export default InfoPage;