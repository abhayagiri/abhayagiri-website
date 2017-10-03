import React, { Component } from 'react';
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
