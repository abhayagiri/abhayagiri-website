import React, { Component } from 'react';
import { translate } from 'react-i18next';
import { tp } from '../../../i18n';
import { i18n } from '../../../i18n';

import ContactOptionsService from 'services/contact-options.service';
import ContactForm from 'components/content/contact/contact-form';
import Link from 'components/shared/link/link';
import Spinner from 'components/shared/spinner/spinner';
import ReCAPTCHA from 'react-google-recaptcha';
import swal from 'sweetalert2';
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

        return this.state.isLoading ? <Spinner /> : (
            <div className="contact container">
                <legend>{contactOption.nameEn}</legend>
                <div className="row">
                    <div className="col-sm-12">
                        <div dangerouslySetInnerHTML={{__html: contactOption.bodyEn}} className="contact-text"></div>
                        {
                            contactOption.active ? <ContactForm contactOptionEmail={contactOption.email} /> : ''
                        }
                    </div>
                    <div className="col-sm-12">
                        <Link to={'/contact'} className="btn btn-secondary">Back</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default translate('contact-option')(ContactOption);
