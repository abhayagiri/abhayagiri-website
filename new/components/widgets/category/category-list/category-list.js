import React, { Component } from 'react';

import Category from '../category/category';

import './category-list.css';

class CategoryList extends Component {
    render() {
        return (
            <div className='category-list'>
               {this.props.categories.map(category=>{
                   return (<Category category={category}/>)
               })}
            </div>
        );
    }
}

export default CategoryList;