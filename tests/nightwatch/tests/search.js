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

    // TODO add tests for new talks page
    // 'Audio Category Test': function(browser) {
    //     browser
    //         .url(browser.launchUrl + '/audio')
    //         .waitForPageToLoad()
    //         .click('#filter-category button')
    //         .click('#filter-category li:nth-child(5) a')
    //         .waitForPageToLoad()
    //         .assert.containsText('body', '20th Anniversary Compilation')
    //     ;
    // },

    'News Search Test (English)': function(browser) {
        browser
            .url(browser.launchUrl + '/news')
            .waitForPageToLoad()
            .setValue(searchInputSelector, 'birthday cake')
            .waitForPageToLoad()
            .assert.containsText('body', 'Born On a Four')
            .assert.containsText('body', 'Rik Center was at the monastery')
            .clearValue(searchInputSelector)
            .setValue(searchInputSelector, 'ในเดือนพฤษภาคมปีนี้')
            .waitForPageToLoad()
            .assert.containsText('body', 'Ajahn Dtun to Visit Abhayagiri')
        ;
    },

    'News Search Test (Thai)': function(browser) {
        browser
            .url(browser.launchUrl + '/th/news')
            .waitForPageToLoad()
            .setValue(searchInputSelector, 'birthday cake')
            .waitForPageToLoad()
            .assert.containsText('body', 'Born On a Four')
            .assert.containsText('body', 'Rik Center was at the monastery')
            .clearValue(searchInputSelector)
            .setValue(searchInputSelector, 'ในเดือนพฤษภาคมปีนี้')
            .waitForPageToLoad()
            .assert.containsText('body', 'พระอาจารย์ตั๋นจะมาเยี่ยมวัดอภัยคีรี')
            .assert.containsText('body', 'ในเดือนพฤษภาคมปีนี้')
        ;
    }
}
