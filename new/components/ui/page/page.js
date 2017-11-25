import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import Spinner from 'components/shared/spinner/spinner';
import Subpage from 'components/ui/subpage/subpage/subpage';
import SubpageList from 'components/ui/subpage/subpage-list/subpage-list';
import './page.css';

export class InfoPage extends Component {

    static propTypes = {
        navPage: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            subpage: null,
            subpages: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.getSubpages(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getSubpages(nextProps);
    }

    async getSubpages(props) {
        this.setState({
            isLoading: true
        });

        const
            subpages = await props.navPage.getSubpages(),

            subpage = subpages.filter(subpage => {
                return subpage.subpath === props.params.splat;
            })[0] || subpages[0];

        this.setState({
            subpage: subpage,
            subpages: subpages,
            isLoading: false
        }, () => {
            props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
    }

    getBreadcrumbs = () => {
        const { subpage } = this.state;
        return [
            {
                title: tp(subpage, 'title'),
                to: `/${subpage.page}/${subpage.subpath}`
            }
        ];
    }

    render() {
        const
            subpage = this.state.subpage,
            subpages = this.state.subpages;

        return !this.state.isLoading ? (
            <div className="page container">
                <div className="row">
                    <div className="col-lg-2">
                        <SubpageList active={subpage} subpages={subpages} />
                    </div>
                    <div className='col-lg-10'>
                        <Subpage subpage={subpage} />
                    </div>
                </div>
            </div>
        ) : <Spinner />;
    }
}

export default translate()(withGlobals(InfoPage, 'navPage'));
