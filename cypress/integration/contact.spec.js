const baseUrl = Cypress.env('baseUrl')

context('Contact', () => {

    beforeEach(() => {
        cy.visit(baseUrl + '/')
    })

    it('contact option without a form', () => {
        cy  .get('#header-menu-button').click()
            .get('#header-menu [href="/contact"]').click()
            .get('#main .contact-options').should('contain', 'Subscribe to our email lists')
            .get('#main a[href="/contact/subscribe-to-our-email-lists').click()
            .get('#main').should('not.contain', 'Send message')
            .get('form.contact-form').should('not.be.visible')
    })

    it('contact option with a form', () => {
        cy  .get('#header-menu-button').click()
            .get('#header-menu [href="/contact"]').click()
            .get('#main .contact-options').should('contain', 'Request an overnight stay')
            .get('#main a[href="/contact/request-an-overnight-stay"]').click()
            .get('#main').should('contain', 'Send message')
            .get('form.contact-form').should('be.visible')
    })

})
