import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import Category from '../category/category';

import './category-list.css';

class CategoryList extends Component {
    static propTypes = {
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            sliceCount: this.props.initial || this.props.paginate || this.props.list.length,
        }

        this.showMore = this.showMore.bind(this);
    }

    showMore() {
        this.setState(state => ({
            sliceCount: state.sliceCount + this.props.paginate,
        }))
    }

    renderPagination() {
        if(this.props.paginate && this.state.sliceCount < this.props.list.length) {
            return (
                <div className="text-center">
                    <button className="btn btn-link cursor-pointer" onClick={this.showMore}>{this.props.t('more')}</button>
                </div>
            )
        }
    }

    render() {
        return (
            <div className='category-list'>
               {this.props.list.slice(0, this.state.sliceCount).map((category, index) => {
                   return (<Category key={index} onClick={this.props.onClick} category={category}/>)
               })}
               { this.renderPagination() }
            </div>
        );
    }
}

export default translate()(CategoryList);
