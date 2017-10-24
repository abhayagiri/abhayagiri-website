import React, { Component } from 'react';
import Link from 'components/shared/link/link';
import Card from 'components/shared/card/card';

import './category.css';

class CategoryItem extends Component {
    render() {
        let category = this.props.category;

        return (
            <Link to={category.href}>
                <Card
                    className="category"
                    thumbnail={category.imageUrl}
                    title={category.title}
                    listItem={true}>
                </Card>
            </Link>
        );
    }
}

export default CategoryItem;