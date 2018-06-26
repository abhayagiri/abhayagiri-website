import React, { Component } from 'react';
import { translate } from 'react-i18next';
import { tp, thp } from '../../../i18n';

import ContactOptionsService from 'services/contact-options.service';
import Spinner from 'components/shared/spinner/spinner';
import Link from 'components/shared/link/link';
import ContactOption from 'components/content/contact/contact-option';
import './contact.css';

export class ContactOptions extends Component {

    componentDidMount() {
        this.fetchData();
    }

    constructor () {
        super();

        this.state = {
            contactOptions: null,
            contactPreambles: null,
            isLoading: false,
        };
    }

    async fetchData() {
        this.setState({
            isLoading: true
        });

        let preambles = await ContactOptionsService.getContactPreambles();
        let results = await ContactOptionsService.getContactOptions();

        this.setState({
            isLoading: false,
            contactPreambles: preambles,
            contactOptions: results
        });
    }

    renderPreamble(preambles, language) {
        let found = preambles.filter(preamble => preamble.key == 'contact.preamble_' + language );
        return found && found.length > 0 ? <div dangerouslySetInnerHTML={{ __html: found[0].valueHtml }} className="contact-text"></div> : '';
    }

    render () {
        let preambles = this.state && this.state.contactPreambles || [];
        let options = this.state && this.state.contactOptions || [];
        const { t, i18n } = this.props;

        return this.state.isLoading ? <Spinner /> : (
            <div className='contact container'>
                <legend>{t('contact')}</legend>
                {this.renderPreamble(preambles, i18n.language)}

                <div className="row">
                    <div className="col-md-6">
                        <ul className="contact-options list-group">
                            {
                                options.map((option, index) =>
                                    <Link className="list-group-item" key={index} to={ 'contact/' + option.slug}>
                                        {tp(option, 'name')}
                                    </Link>
                                )
                            }
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
}

export default translate('contact')(ContactOptions);
