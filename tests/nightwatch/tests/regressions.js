const searchInputSelector = '.input-append input';

module.exports = {

    'before': function(browser) {
        browser
            .useCss()
        ;
    },

    'after': function(browser) {
        browser.end();
    },

    'Non-standard URL Test': function(browser) {
        browser
            .url(browser.launchUrl + '/news')
            .waitForPageToLoad()
            .setValue(searchInputSelector, 'somkid')
            .waitForPageToLoad()
            .assert.containsText('body', 'Somkid')
            .assert.containsText('body', 'a professional photographer')
            .clickOnText('Somkid\'s Photography')
            .waitForPageToLoad()
            .assert.containsText('body', 'a professional photographer')
            .url(browser.launchUrl + '/news/somkid\'s-photography')
            .assert.containsText('body', 'a professional photographer')
        ;
    }
}

