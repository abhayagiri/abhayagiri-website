import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import AuthorService from 'services/author.service';

export class CategoryTeachers extends Component {

    static propTypes = {
        setBreadcrumbs: PropTypes.func.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            teachers: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.updateBreadcrumbs();
        this.fetchTeachers();
    }

    async fetchTeachers() {
        const teachers = await AuthorService.getAuthors({minTalks: 1});
        this.setState({
            teachers: teachers,
            isLoading: false
        });
    }

    updateBreadcrumbs = () => {
        this.props.setBreadcrumbs(() => {
            return [
                {
                    title: this.props.t('teachers'),
                    to: '/talks/teachers'
                }
            ];
        });
    }

    getCategoryList() {
        if (this.state.teachers) {
            return this.state.teachers.map((teacher) => {
                return {
                    imageUrl: teacher.imageUrl,
                    title: tp(teacher, 'title'),
                    href: teacher.talksPath,
                    visiting: teacher.visiting,
                };
            });
        } else {
            return [];
        }
    }

    render() {
        const residents = this.getCategoryList().filter(category => !category.visiting);
        const visitors = this.getCategoryList().filter(category => category.visiting);

        return !this.state.isLoading ? (
            <div className="mb-5">
                { residents.length > 0 &&
                    <article>
                        <h4>{this.props.t('residents and former residents')}</h4>
                        <CategoryList list={residents} paginate={5} />
                    </article>
                }
                <div className="clearfix"></div>
                { visitors.length > 0 &&
                    <article>
                        <h4>{this.props.t('visiting ajahns')}</h4>
                        <CategoryList list={visitors} paginate={5} />
                    </article>
                }
            </div>
         ) : <Spinner />;
    }
}

export default translate('talks')(
    withBreadcrumbs(CategoryTeachers)
);
