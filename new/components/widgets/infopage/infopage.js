import React, { Component } from 'react';

import SubpageList from '../subpage/subpage-list/subpage-list';

import './infopage.css';

class InfoPage extends Component {
    render() {
        console.log(this.props.page);
        //let subpage = this.props.routes[2].name;
        //let subpages = this.props.page.subpages;
        
        return this.props.page ? (
            <div className="infopage">
                <div className="row">
                    <div className="col-3">
                        <SubpageList subpages={this.props.page.subpages}/>
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