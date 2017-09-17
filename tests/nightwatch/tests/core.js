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

    'Books Test': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-books')
            .waitForPageToLoad()
            .assert.containsText('body', 'Books')
            .assert.containsText('body', 'PDF')
            .setValue(searchInputSelector, "Don't push")
            .waitForPageToLoad()
            .assert.containsText('body', 'Amaro')
        ;
    },

    'Community Test (English)': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-community')
            .waitForPageToLoad()
            .assert.containsText('body', 'Community')
            .assert.containsText('body', 'Residents')
            .assert.containsText('body', 'Ajahn Pasanno (Abbot)')
            .clickOnText('Ajahn Pasanno')
            .waitForPageToLoad()
            .assert.containsText('body', 'Ajahn Pasanno took ordination in Thailand')
        ;
    },

    'Community Test (Thai)': function(browser) {
        browser
            .url(browser.launchUrl + '/th/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-community')
            .waitForPageToLoad()
            .assert.containsText('body', 'หมู่คณะ')
            .assert.containsText('body', 'พระภิกษุสงฆ์ นักบวชและอุบาสิกา')
            .assert.containsText('body', 'หลวงพ่อ ปสนฺโน (เจ้าอาวาส)')
            .clickOnText('หลวงพ่อ ปสนฺโน')
            .waitForPageToLoad()
            .assert.containsText('body', 'หลวงพ่อปสนฺโนได้รับการอุปสมบทเป็นพระภิกษุสงฆ์ในปี')
        ;
    },

    'Contact Form Test': function(browser) {
        browser
            .url(browser.launchUrl + '/contact')
            .waitForPageToLoad()
            .assert.containsText('body', 'Contact Form')
            .setValue('#name', 'John Doe')
            .setValue('#email', 'john@example.com')
            .setValue('#message', 'great work!')
            .pause(2000)
        ;
    },

    'Gallery Test': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-gallery')
            .waitForPageToLoad()
            .assert.containsText('body', 'Gallery')
            .click('#gallery > div:first-child > a')
            .waitForPageToLoad()
            .assert.visible('a.thumbnail')
        ;
    },

    'Reflections Test (Thai)': function(browser) {
        browser
            .url(browser.launchUrl + '/th/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-reflections')
            .waitForPageToLoad()
            .assert.containsText('body', 'แง่ธรรม')
            .assert.containsText('body', 'อ่านต่อ')
            .click('.dataTable > tbody > tr:nth-child(2) .btn')
            .waitForPageToLoad()
            .assert.containsText('body', 'กลับสู่ด้านบน')
        ;
    },

    'Talks Test': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-talks')
            // TODO Refactor new pages into .waitForPageToLoad()
            // .waitForPageToLoad()
            .waitForElementVisible('.card-text', 10000)
            .assert.containsText('body', 'Talks')
            .assert.containsText('body', 'Play')
        ;
    },

    'Visitor Test (English)': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .assert.title('Abhayagiri Buddhist Monastery')
            .assert.containsText('body', 'News')
            .assert.containsText('body', 'Calendar')
            // .clickOnText('More News')
            .execute(function() {
                $('.btn[href="/news"').click();
            })
            .waitForPageToLoad()
            .assert.containsText('body', 'back to top')
            .assert.containsText('body', 'Read More')
            // .clickOnText('Directions')
            .execute(function() {
                $('.btn[href="/visiting/directions"]').click();
            })
            .waitForPageToLoad()
            .assert.containsText('legend', 'Directions')
            .assert.containsText('body', '16201 Tomki Road')
        ;
    },

    'Visitor Test (Thai)': function(browser) {
        browser
            .url(browser.launchUrl + '/th/home')
            .waitForPageToLoad()
            .assert.title('Abhayagiri Buddhist Monastery')
            .assert.containsText('body', 'ข่าว')
            .assert.containsText('body', 'ปฏิทิน')
            // .clickOnText('ต่อไป')
            .execute(function() {
                $('.btn[href="/th/news"').click();
            })
            .waitForPageToLoad()
            .assert.containsText('body', 'กลับสู่ด้านบน')
            .assert.containsText('body', 'อ่านต่อ')
            //.clickOnText('เส้นทาง')
            .execute(function() {
                $('.btn[href="/th/visiting/directions-thai"]').click();
            })
            .waitForPageToLoad()
            .assert.containsText('legend', 'เส้นทางมาวัด')
            .assert.containsText('body', '16201 Tomki Road')
        ;
    }

};
