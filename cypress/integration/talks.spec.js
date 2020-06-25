const baseUrl = Cypress.env('baseUrl')

context('talks', () => {

    it('latest talks', () => {
        cy  .visit(baseUrl + '/')
            .get('#header-menu-button').click()
            .get('#header-menu [href="/talks"]').click()
            .get('#main .header').should('contain', 'Latest Dhamma Talks')
            .get('#main .talk').should('contain', 'Running Away from Phantoms')
    })

    it('collection cards on phone', () => {
        cy  .visit(baseUrl + '/talks')
            .get('#main :nth-child(1) > .card > a').click()
            .url().should('eq', baseUrl + '/talks/collections')
            .get('#main :nth-child(1) > .card').should('be.visible')
            .viewport(600, 400)
            .get('#main :nth-child(1) > .card').should('be.visible')
    })

    it('old redirect in english', () => {
        cy.visit(baseUrl + '/talks/6483-dhamma-talks-2019')
            .url().should('eq', baseUrl + '/talks/collections/1/1-dhamma-talks-2019')
            .get('#main').should('contain', 'Dhamma talks from Abhayagiri for 2019')
    })

    it('old redirect in thai', () => {
        cy.visit(baseUrl + '/th/audio/dhamma-talks-2019')
            .url().should('eq', baseUrl + '/th/talks/collections/1/1-dhamma-talks-2019')
            .get('#main').should('contain', 'รวมพระธรรมเทศนาและข้อคิดจากพระอาจารย์')
    })

    it('single talk', () => {
        cy.visit(baseUrl + '/talks/1')
            .get('#main').should('contain', 'Running Away from Phantoms')
    })

    it('nonexistent talk', () => {
        cy.visit(baseUrl + '/talks/xyz')
            .get('main').should('contain', 'Not Found')
    })

})
