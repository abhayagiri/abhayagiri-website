const baseUrl = Cypress.env('baseUrl')

context('Home', () => {

    beforeEach(() => {
        cy.visit(baseUrl + '/')
    })

    it('visit home page in English', () => {
        cy  .title().should('contain', 'Abhayagiri Monastery')
            .get('#main .news h2')
            .should('contain', 'News')
            .get('#main .calendar h2')
            .should('contain', 'Calendar')

    })

    it('visit home page in Thai', () => {
        cy  .get('#header-language').click()
            .url().should('eq', baseUrl + '/th')
            .title().should('contain', 'วัดป่าอภัยคีรี')
            .get('#main .news h2')
            .should('contain', 'ข่าว')
            .get('#main .calendar h2')
            .should('contain', 'ปฏิทิน')
    })

    it('find directions in English', () => {
        cy  .get('#header-menu .frame').should('not.be.visible')
            .get('#header-menu-button').click()
            .get('#header-menu .frame').should('be.visible')
            .get('[href="/visiting"]').click()
            .url().should('eq', baseUrl + '/visiting')
            .get('.subpage [href="/visiting/directions"]').click()
            .url().should('eq', baseUrl + '/visiting/directions')
            .get('#main .body')
            .should('contain', 'Directions')
            .should('contain', '16201 Tomki Road')
    })

    it('find directions in Thai', () => {
        cy  .get('#header-language').click()
            .url().should('eq', baseUrl + '/th')
            .get('#header-menu .frame').should('not.be.visible')
            .get('#header-menu-button').click()
            .get('#header-menu .frame').should('be.visible')
            .get('[href="/th/visiting"]').click()
            .url().should('eq', baseUrl + '/th/visiting')
            .get('.subpage [href="/th/visiting/directions"]').click()
            .url().should('eq', baseUrl + '/th/visiting/directions')
            .get('#main .body')
            .should('contain', 'เส้นทางมาวัด')
            .should('contain', '16201 Tomki Road')
    })

})
