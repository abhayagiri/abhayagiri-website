import React, { Component } from 'react';

class Spinner extends Component {
    render() {
        return (
            <div className='spinner'>
                <i className="la la-spinner fa-spin fa-3x fa-fw"></i>
            </div>
        );
    }
}

export default Spinner;
