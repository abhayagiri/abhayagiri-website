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
            .click('a[href="/construction"]')
            .waitForPageToLoad()
            .clickOnText('New Entry')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.contain('Construction Entry');

        browser
            .setValue('form #title', 'Test123')
            .clickOnText('Submit')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.not.contain('Construction Entry');
    },

    'Mahapanel Search Test': function(browser) {
        browser
            .click('button[title~=MENU]')
            .click('a[href="/construction"]')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.contain('Construction');

        browser
            .setValue(searchInputSelector, 'concrete')
            .waitForPageToLoad();
        browser.expect.element('body').text.to.contain('Retaining Wall');
    }

};
