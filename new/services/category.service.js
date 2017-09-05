import categories from '../data/categories.json';

class CategoryService {

    static getCategory(slug) {
        for (let category of categories) {
            if (category.slug === slug) {
                return category;
            }
        }
        throw new Error('Category not found ' + slug);
    }

    static getCategories() {
        return categories;
    }
}

export default CategoryService;
