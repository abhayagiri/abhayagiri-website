const searchInputSelector = '.dataTables_filter input';

module.exports = {

    'before': function(browser) {
        browser
            .useCss()
            .url(browser.launchUrl + '/mahapanel_bypass?email=root@localhost')
            .waitForPageToLoad()
            .pause(1000) // HACK
        ;
    },

    'after': function(browser) {
        browser.end();
    },

    'Mahapanel Enter Data Test': function(browser) {
        browser
            .click('button[title~=MENU]')
            .click('a[href="/subpages"]')
            .waitForPageToLoad()
            .clickOnText('New Entry')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.contain('Subpages Entry');

        browser
            .setValue('form #page', 'support')
            .setValue('form #title', 'Test123')
            .clickOnText('Submit')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.not.contain('Subpages Entry');
    },

    'Mahapanel Search Test': function(browser) {
        browser
            .click('button[title~=MENU]')
            .click('a[href="/subpages"]')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.contain('Subpages');

        browser
            .setValue(searchInputSelector, 'daily')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.contain('Daily Schedule');
        browser.expect.element('body').text.to.contain('daily-schedule');
    }

};
