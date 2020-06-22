import React, { Component } from 'react';

import Card from './card';

class CategoryItem extends Component {
    render() {
        let category = this.props.category;

        return (
            <Card
                className="category"
                thumbnail={category.imageUrl}
                title={category.title}
                listItem={true}
                href={category.href}>
            </Card>
        );
    }
}

export default CategoryItem;
