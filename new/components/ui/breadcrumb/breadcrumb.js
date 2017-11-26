import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Emitter from 'tiny-emitter';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import Link from 'components/shared/link/link';
import PageService from 'services/page.service';
import './breadcrumb.css';

export function withBreadcrumbs(SubComponent) {

    class BreadcrumbWrapper extends Component {

        static contextTypes = {
            globals: PropTypes.object.isRequired
        }

        setBreadcrumbs = (breadcrumbs) => {
            this.context.globals.set('breadcrumbs', breadcrumbs);
        }

        render() {
            return React.createElement(
                SubComponent,
                { ...this.props,
                    setBreadcrumbs: this.setBreadcrumbs }
            );
        }
    }

    return BreadcrumbWrapper;
}

export class Breadcrumb extends Component {

    static propTypes = {
        breadcrumbs: PropTypes.oneOfType([
            PropTypes.array,
            PropTypes.func
        ]),
        navPage: PropTypes.object.isRequired
    }

    getBreadcrumbs() {
        const pageToBreadcrumb = (page) => {
            return {
                title: tp(page, 'title'),
                to: page.path
            };
        };
        let breadcrumbs = [
            pageToBreadcrumb(PageService.getPage('home'))
        ];
        if (this.props.navPage.slug !== 'home') {
            breadcrumbs.push(pageToBreadcrumb(this.props.navPage));
        }
        let subcrumbs = this.props.breadcrumbs;
        if (typeof subcrumbs === 'function') {
            breadcrumbs = breadcrumbs.concat(subcrumbs());
        } else if (subcrumbs) {
            breadcrumbs = breadcrumbs.concat(subcrumbs);
        }
        return breadcrumbs.map((breadcrumb, index) => {
            if (!breadcrumb) {
                breadcrumb = { loading: true };
            }
            breadcrumb['active'] = index === breadcrumbs.length - 1;
            return breadcrumb;
        });
    }

    render() {
        return (
            <div id="breadcrumb-container">
                <div className="container">
                    <ol className="breadcrumb">
                        {this.getBreadcrumbs().map((breadcrumb, index) => {
                            const
                                breadcrumbClass = 'breadcrumb-item ' +
                                    (index === 1 ? 'breadcrumb-navpage' :
                                    (breadcrumb.active ? 'active' : '')),
                                title = tp(breadcrumb, 'title');
                            return (
                                <li key={index} className={breadcrumbClass}>
                                    {
                                        breadcrumb.loading ?
                                            '…' : (
                                        breadcrumb.active ?
                                            breadcrumb.title : (
                                        /* else */
                                            <Link to={breadcrumb.to}>{breadcrumb.title}</Link>
                                        ))
                                    }
                                </li>
                            );
                        })}
                    </ol>
                </div>
            </div>
        );
    }
}

export default withGlobals(Breadcrumb, ['breadcrumbs', 'navPage']);
