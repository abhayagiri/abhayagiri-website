import React, { Component } from 'react';

import Link from 'components/shared/link/link';
import Card from 'components/shared/card/card';
import './category-card.css';

class CategoryItem extends Component {

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
                    fluid={true}
                    title={category.title}
                    subtitle={subcategory && subcategory.title}
                    body={category.description}
                    links={category.links}>
                </Card>
            </div>
        );
    }
}

export default CategoryItem;
