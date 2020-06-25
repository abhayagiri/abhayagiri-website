const baseUrl = Cypress.env('baseUrl')

context('Search', () => {

    beforeEach(() => {
        cy.visit(baseUrl + '/')
    })

    it('algolia search works', () => {
        cy  .get('#header-search')
            .should('not.be.visible')
            .get('#header-search-button').click()
            .get('#header-search')
            .should('be.visible')
            .get('.ais-SearchBox input').type('buddhism')
            .get('.ais-Hits-list').should('contain', 'What is Buddhism?')
            .get('#header-search h2 a[href="/books/1-what-is-buddhism"]').click()
            .url().should('eq', baseUrl + '/books/1-what-is-buddhism')
    })

})
