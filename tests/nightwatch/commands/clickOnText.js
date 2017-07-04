exports.command = function clickOnText(text) {
    return this
        .useXpath()
        .click('//*[text()[contains(., "' + text + '")]]')
        .useCss()
    ;
}
