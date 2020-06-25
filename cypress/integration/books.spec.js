const baseUrl = Cypress.env('baseUrl')

context('Books', () => {

    beforeEach(() => {
        cy.visit(baseUrl + '/')
    })

    it('see important information when requesting a book', () => {
        cy  .get('#header-menu-button').click()
            .get('[href="/books"]').click()
            .get('#main h1').should('contain', 'What is Buddhism?')
            .get('#main form .btn').click()
            .get('#main h1').should('contain', 'Book Selection')
            .get('[href="/books/request"]').click()
            .get('#main h1').should('contain', 'Book Request')
            .get('.book-cart-country').should('not.contain', 'important information')
            .get('input[name=country]').type('thailand')
            .get('textarea[name=comments]').click()
            .get('.book-cart-country').should('contain', 'important information')
            .get('input[name=country]').type('{selectall}us')
            .get('textarea[name=comments]').click()
            .get('.book-cart-country').should('not.contain', 'important information')
    })

})
