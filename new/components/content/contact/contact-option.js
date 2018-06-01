import React, { Component } from 'react';
import { translate } from 'react-i18next';
import { tp, thp } from '../../../i18n';

import ContactOptionsService from 'services/contact-options.service';
import ContactForm from 'components/content/contact/contact-form';
import Spinner from 'components/shared/spinner/spinner';
import Link from 'components/shared/link/link';
import './contact.css';

export class ContactOption extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isLoading: true,
        }
    }

    componentDidMount() {
        this.getContactOption(this.props);
    }

    async getContactOption(props) {
        let contactOptionSlug = props.params.contactOption;

        const contactOption = await ContactOptionsService.getContactOption(contactOptionSlug);

        this.setState({
            isLoading: false,
            contactOption: contactOption,
        })
    }

    render() {
        let contactOption = this.state && this.state.contactOption;
        const { t, i18n } = this.props;

        return this.state.isLoading ? <Spinner /> : (
            <div className="contact container">
                <legend>{tp(contactOption, 'name')}</legend>
                <div className="row">
                    <div className="col-sm-12">
                        <div className="contact-text">{thp(contactOption, 'body')}</div>
                        {
                            contactOption.active ? <ContactForm contactOptionEmail={contactOption.email} /> : ''
                        }
                    </div>
                    <div className="col-sm-12">
                        <Link to={'/contact'} className="btn btn-secondary">{t('back')}</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default translate('contact')(ContactOption);
