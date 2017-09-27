import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';

class Residents extends Component {
    constructor(){
        super();
        
    }

    render() {
        return (
            <div className='residents'>
                
            </div >
        );
    }
}

const ResidentsTranslate = translate('talks')(Residents);

export default ResidentsTranslate;
