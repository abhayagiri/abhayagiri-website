const searchInputSelector = '.input-append input';

// TODO Refactor new pages into .waitForPageToLoad()
// .waitForPageToLoad()

module.exports = {

    'before': function(browser) {
        browser
            .useCss()
        ;
    },

    'after': function(browser) {
        browser.end();
    },

    'latest talks test': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-talks')
            .waitForElementVisible('.latest-talks', 10000)
            .assert.containsText('body', 'Dhamma Talks')
            .assert.containsText('body', 'Play')
        ;
    },

    'collections cards on phones test': function(browser) {
        browser
            .execute(function(url) {
                window.open(url, 'phone', 'height=600,width=400');
            }, [browser.launchUrl + '/new/talks/collections'], function() {
                browser
                    .switchWindow('phone')
                    .waitForElementVisible('.category-list', 10000)
                    .assert.visible('.card')
                    .closeWindow();
            })
        ;
    }

};
