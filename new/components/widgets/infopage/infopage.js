import React, { Component } from 'react';

import './infopage.css';

class InfoPage extends Component {
    render() {
        return (
            <div className="infopage">
                <div className="row">
                    <div className="col-3">
                    
                    </div>
                    <div className='col-9'>
                        {this.props.children}
                    </div>
                </div>
            </div>
        );
    }
}

export default InfoPage;