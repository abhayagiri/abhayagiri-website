import React, { Component } from 'react';
import Link from 'components/shared/link/link';
import Card from 'components/shared/card/card';

import './category-card.css';

class CategoryItem extends Component {
    isActive(title) {
        return this.context.pageName === title ? 'nav-link active' : 'nav-link';
    }

    render() {
        let category = this.props.category;
        let subcategory = null;

        if (category.links) {
            subcategory = category.links.filter(link => link.active === true)[0];
        }

        return (
            <div>
                <Card
                    className=""
                    thumbnail={category.imageUrl}
                    title={category.title}
                    subtitle={subcategory && subcategory.title}
                    links={category.links}>
                </Card>


            </div>
        );
    }
}

export default CategoryItem;