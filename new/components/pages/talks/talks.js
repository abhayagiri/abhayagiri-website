import React, { Component } from 'react';
import { translate } from 'react-i18next';
import axios from 'axios';

import CategoryList from '../../widgets/category/category-list/category-list';
import TalkList from './talk-list/talk-list';
import TalksService from '../../../services/talks.service';
import Spinner from '../../widgets/spinner/spinner';

import './talks.css';
import categories from '../../../data/categories.json';

class Talks extends Component {

    constructor() {
        super();

        this.state = {
            talks: [],
            searchText: '',
            currentPage: 1,
            pageSize: 10,
            totalPages: 1,
            category: categories[0],
            isLoading: false,
            teacher: false,
            genre: false,
            collapse: false
        }
    }

    componentWillMount() {
        let page = this.props.params.page || this.state.currentPage;
        this.getTalks({ page: page });
    }

    componentWillReceiveProps(nextProps) {
        let page = nextProps.params.page || this.state.currentPage;
        this.getTalks({ page: page });
    }

    async getTalks(filters) {
        this.setState({
            isLoading: true
        });
        let request = await TalksService.getTalks(filters);
        let talks = request.result;
        let currentPage = request.page;
        let totalPages = request.totalPages;
        let pageSize = request.pageSize;

        this.setState({
            talks: talks,
            currentPage: currentPage,
            totalPages: totalPages,
            pageSize: pageSize,
            isLoading: false
        });
    }

    handleSearchChange(event) {
        this.setState({ searchText: event.target.value });
    }

    async handleSearchKeyPress(event) {
        if (event.key === 'Enter') {
            this.searchTalks()
        }
    }

    searchTalks() {
        this.getTalks({
            searchText: this.state.searchText
        });
    }

    setCategory(category) {
        this.setState({
            category: category
        });
        this.getTalks({
            categorySlug: category.slug
        });
    }

    setGenre(genre) {
        this.setState({
            genre: genre
        });
    }

    getCategoryTitle(category) {
        // TODO refactor
        const key = this.props.i18n.language === 'en' ? 'titleEn' : 'titleTh';
        return category[key];
    }

    getCategoryClass(category) {
        let categoryClass = "nav-item nav-link category-link";
        return categoryClass + (this.state.category.slug === category.slug ? ' active' : '');
    }

    toggleCollapse(){
        this.setState({
            collapse: !this.state.collapse
        });
    }

    render() {
        const { t } = this.props;
        return (
            <div className='categories '>
                <nav className={"navbar navbar-toggleable-sm navbar-light bg-faded"} style={{ 'backgroundColor': '#e3f2fd' }}>
                    <div className="container">
                        <div className={"navbar-collapse"} id="navbarNavAltMarkup">
                            <div className="navbar-nav mr-auto  hidden-md-down">
                                {categories.map((category, index) => {
                                    return <a key={index} className={this.getCategoryClass(category)} onClick={this.setCategory.bind(this, category)}>{this.getCategoryTitle(category)}</a>
                                })}
                            </div>
                            <div className="form-inline my-2 my-lg-0 float-right">
                                <input className="form-control mr-sm-2" type="text" placeholder={t('search')}
                                    value={this.state.searchText}
                                    onChange={this.handleSearchChange.bind(this)}
                                    onKeyPress={this.handleSearchKeyPress.bind(this)} />
                                <button className="btn btn-outline-primary my-2 my-sm-0" onClick={this.searchTalks.bind(this)}>{t('search')}</button>
                            </div>
                        </div>
                    </div>
                </nav>

                <div className="content container">
                    {this.state.categorySelection && this.state.category.title === 'Teachers' &&
                        <CategoryList categories={categories} onClick={this.setCategory.bind(this)} />
                    }

                    {this.state.categorySelection && this.state.category.title === 'Genres' &&
                        <CategoryList categories={genres} onClick={this.setGenre.bind(this)} />
                    }

                    {!this.state.categorySelection &&
                        <div className="row">
                            {/*{this.state.category && (this.state.teacher || this.state.genre) ? <div className="row">*/}
                            <div className="col-lg-3 hidden-md-down">
                                <div className="card" >
                                    <img className="card-img-top" src={this.state.category.image} alt="Card image cap" height="200px" />
                                    <div className="card-block">
                                        <h4 className="card-title">{this.state.category.title}</h4>
                                        <div className="card-text" dangerouslySetInnerHTML={{ __html: this.state.category.descriptionEn }} />
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-9 col-md-12">
                                {this.state.isLoading ? <Spinner /> : <TalkList pageSize={this.state.pageSize} totalPages={this.state.totalPages} currentPage={this.state.currentPage} talks={this.state.talks} />}
                            </div>
                            {/*</div> : ''}*/}
                        </div>}
                </div>
            </div>
        );
    }
}

const TalksWithTranslate = translate('talks')(Talks);

export default TalksWithTranslate;
