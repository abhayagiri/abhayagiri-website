// As of 2019-06-16, nightwatch has a bug that does not automatically start
// selenium correctly. See https://github.com/nightwatchjs/nightwatch/issues/1788.
//
// As a workaround:
//
//     node_modules/.bin/selenium-standalone install
//     npm run build
//     php artisan serve &
//     node_modules/.bin/selenium-standalone start &
//     # Wait for both the server and selenium to start...
//     node_modules/.bin/nightwatch -c tests/nightwatch/nightwatch.local-no-autostart.js

module.exports = {
    "src_folders": [
        "tests/nightwatch/tests"
    ],
    "output_folder": "tests/nightwatch/reports",
    "custom_commands_path": "tests/nightwatch/commands",

    "selenium": {
        "start_process": false,
        "host": "127.0.0.1",
        "port": 4444
    },

    "test_settings": {
        "default": {
            "launch_url": "http://localhost:8000",
            "desiredCapabilities": {
                "browserName": "chrome",
	        "chromeOptions": {
	            "args": [
                        "--headless"
                    ],
	        },
                "javascriptEnabled": true
            }
        }
    }
}
