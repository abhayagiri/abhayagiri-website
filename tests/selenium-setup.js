#!/usr/bin/env node

"require strict";

const path = require('path');

const binpath = path.join(__dirname, '..', 'node_modules', '.bin');

require('selenium-download').ensure(binpath, function(error) {
    if (error) {
        console.error(error);
    } else {
        console.log('✔ Selenium & chromedriver downloaded to:', binpath);
    }
});
