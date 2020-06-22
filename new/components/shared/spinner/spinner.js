import React, { Component } from 'react';

import './spinner.css';

class Spinner extends Component {
    render() {
        return (
            <div className='spinner'>
                <i className="fa fa-spinner fa-spin fa-3x fa-fw"></i>
            </div>
        );
    }
}

export default Spinner;
