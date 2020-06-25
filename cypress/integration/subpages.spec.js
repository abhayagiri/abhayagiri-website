const baseUrl = Cypress.env('baseUrl')

context('Subpages', () => {

    beforeEach(() => {
        cy.visit(baseUrl + '/')
    })

    it('residents in english', () => {
        cy  .get('#header-menu-button').click()
            .get('#header-menu [href="/community"]').click()
            .url().should('eq', baseUrl + '/community')
            .get('#header-page').should('contain', 'Community')
            .get('#main').should('contain', 'Ajahn Pasanno')
    })

    it('residents in thai', () => {
        cy  .get('#header-language').click()
            .get('#header-menu-button').click()
            .get('#header-menu [href="/th/community"]').click()
            .url().should('eq', baseUrl + '/th/community')
            .get('#header-page').should('contain', 'หมู่คณะ')
            .get('#main').should('contain', 'หลวงพ่อ ปสนฺโน')
    })

})
