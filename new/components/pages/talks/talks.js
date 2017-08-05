import React, { Component } from 'react';
import { translate } from 'react-i18next';
import axios from 'axios';

import CategoryList from '../../widgets/category/category-list/category-list';
import TalkList from './talk-list/talk-list';
import TalksService from '../../../services/talks.service';
import Spinner from '../../widgets/spinner/spinner';

import './talks.css';
import categories from './categories.json';

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
            genre: false
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
        console.log(this.state.searchText);
        this.getTalks({
            searchText: this.state.searchText
        });
    }

    setCategory(category) {
        this.setState({
            category: category
        });
        this.getTalks({
            category: category.dbName
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

    render() {
        const { t } = this.props;
        return (
            <div className='categories '>
                <nav className="navbar navbar-toggleable-md navbar-light bg-faded" style={{ 'background-color': '#e3f2fd' }}>
                    <div className="container">
                        <button className="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span className="navbar-toggler-icon"></span>
                        </button>
                        <div className="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div className="navbar-nav mr-auto">
                                {categories.map(category => {
                                    return <a className={this.getCategoryClass(category)} onClick={this.setCategory.bind(this, category)}>{this.getCategoryTitle(category)}</a>
                                })}
                            </div>
                            <ul class="navbar-nav mr-auto"></ul>
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
                            <div className="col-3">
                                <div className="card" >
                                    <img className="card-img-top" src={this.state.category.image} alt="Card image cap" height="200px" />
                                    <div className="card-block">
                                        <h4 className="card-title">{this.state.category.title}</h4>
                                        <div className="card-text" dangerouslySetInnerHTML={{__html: this.state.category.descriptionEn}} />
                                    </div>
                                </div>
                            </div>
                            <div className="col-9">
                                {this.state.isLoading ? <Spinner /> : <TalkList pageSize={this.state.pageSize} totalPages={this.state.totalPages} currentPage={this.state.currentPage} talks={this.state.talks} />}
                            </div>
                            {/*</div> : ''}*/}
                        </div>}

                </div>


            </div>
        );
    }
}

export default translate('talks')(Talks);