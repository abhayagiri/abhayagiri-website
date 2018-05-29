import React, { Component } from 'react';
import { translate } from 'react-i18next';
import { tp, thp } from '../../../i18n';
import { i18n } from '../../../i18n';

import ContactOptionsService from 'services/contact-options.service';
import Link from 'components/shared/link/link';
import ContactOption from 'components/content/contact/contact-option';
import ReCAPTCHA from 'react-google-recaptcha';
import swal from 'sweetalert2';
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
            loading: false,
        };
    }

    async fetchData() {
        this.setState({
            loading: true
        });

        let preambles = await ContactOptionsService.getContactPreambles();
        let results = await ContactOptionsService.getContactOptions();

        this.setState({
            loading: false,
            contactPreambles: preambles,
            contactOptions: results
        });
    }

    renderPreamble(preambles, language) {
        let found = preambles.filter(preamble => preamble.key == 'contact.preamble_' + language );
        return found && found.length > 0 ? <div dangerouslySetInnerHTML={{ __html: found[0].value }} className="contact-text"></div> : '';
    }

    render () {
        let preambles = this.state && this.state.contactPreambles || [];
        let options = this.state && this.state.contactOptions || [];
        const { t, i18n } = this.props;

        return this.state.isLoading ? <Spinner /> : (
            <div className='contact container'>
                <legend>{t('Contact')}</legend>
                {this.renderPreamble(preambles, i18n.language)}

                <div className="row">
                    <div className="col-md-6">
                        <ul className="contact-options list-group">
                            {options.map((option, index) =>
                                <li className="list-group-item" key={index}>
                                    <Link to={ 'contact/' + option.slug}>
                                        {t(option.nameEn, 'title')}
                                    </Link>
                                </li>)
                            }
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
}

export default translate('contact-options')(ContactOptions);
