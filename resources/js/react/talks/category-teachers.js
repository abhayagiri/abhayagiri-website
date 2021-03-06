import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { pageWrapper } from '../shared/util';
import CategoryList from '../shared/category-list';
import Spinner from '../shared/spinner';
import AuthorService from '../services/author.service';

export class CategoryTeachers extends Component {

    static propTypes = {
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
        this.fetchTeachers();
    }

    async fetchTeachers() {
        const teachers = await AuthorService.getAuthors({minTalks: 1});
        this.setState({
            teachers: teachers,
            isLoading: false
        });
    }

    getCategoryList() {
        if (this.state.teachers) {
            return this.state.teachers.map((teacher) => {
                return {
                    imageUrl: teacher.imageUrl,
                    title: this.props.tp(teacher, 'title'),
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
                        <CategoryList list={residents} initial={10} paginate={residents.length - 10} />
                    </article>
                }
                <div className="clearfix"></div>
                { visitors.length > 0 &&
                    <article>
                        <h4>{this.props.t('visiting ajahns')}</h4>
                        <CategoryList list={visitors} initial={10} paginate={visitors.length - 10} />
                    </article>
                }
            </div>
         ) : <Spinner />;
    }
}

export default pageWrapper('talks')(CategoryTeachers);
