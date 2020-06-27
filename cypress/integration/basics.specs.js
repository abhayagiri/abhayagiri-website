const baseUrl = Cypress.env('baseUrl')

const randomName = () => {
    const number = Cypress._.random(0, 1e6);
    return 'random-' + number;
};

context('admin', () => {

    it('can create a new playlist', () => {
        Cypress.on('uncaught:exception', (err, runnable) => {
            return false
        })

        const title = randomName();
        cy  .visit(baseUrl + '/admin/login')
            .get('a[href="' + baseUrl + '/admin/login/dev-bypass"]').click()
            .get('a.nav-link[href="' + baseUrl + '/admin/playlists"]').click()
            .get('a[href="' + baseUrl + '/admin/playlists/create"]').click()
            .get('select[name="group_id"]').select('1')
            .get('input[name="title_en"]').type(title)
            .get('input[name="youtube_playlist_id"]').type(randomName())
            .get('button[type="submit"]').click()
            .get('a.nav-link[href="' + baseUrl + '/admin/playlists"]').click()
            .get('#crudTable').should('contain', title);
    })

})

context('books', () => {

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

context('contact', () => {

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

context('home', () => {

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

context('search', () => {

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

context('subpages', () => {

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
