module.exports = {

    'before': function(browser) {
        browser
            .useCss()
        ;
    },

    'after': function(browser) {
        browser.end();
    },

    'Audio Test': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .click('#btn-menu')
            .click('#btn-audio')
            .waitForPageToLoad()
            .assert.containsText('body', 'Audio')
            .assert.containsText('body', 'Play')
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
            .clickOnText('Ajahn Pasanno')
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
            .assert.containsText('body', 'Bhikkhu Ordination')
            .clickOnText('Bhikkhu Ordination')
            .waitForPageToLoad()
            .assert.containsText('body', 'Bhikkhu Ordination')
            .assert.visible('a.thumbnail')
        ;
    },

    'Visitor Test (English)': function(browser) {
        browser
            .url(browser.launchUrl + '/home')
            .waitForPageToLoad()
            .assert.title('Abhayagiri Buddhist Monastery')
            .assert.containsText('body', 'News')
            .assert.containsText('body', 'Calendar')
            .clickOnText('More News')
            .waitForPageToLoad()
            .assert.containsText('body', 'back to top')
            .assert.containsText('body', 'Read More')
            .clickOnText('Directions')
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
            .clickOnText('ต่อไป')
            .waitForPageToLoad()
            .assert.containsText('body', 'กลับสู่ด้านบน')
            .assert.containsText('body', 'อ่านต่อ')
            .clickOnText('เส้นทาง')
            .waitForPageToLoad()
            .assert.containsText('legend', 'เส้นทางมาวัด')
            .assert.containsText('body', '16201 Tomki Road')
        ;
    }

};
