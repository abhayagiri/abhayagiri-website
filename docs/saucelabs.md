
## Test Frontend Using SauceLabs

Get a SauceLabs account and install [Sauce Connect](https://wiki.saucelabs.com/display/DOCS/Sauce+Connect+Proxy). Then,

```sh
export SAUCE_USERNAME=abhayagiri
export SAUCE_ACCESS_KEY=...
sc -u $SAUCE_USERNAME -k $SAUCE_ACCESS_KEY &
php artisan serve &
npm run build
php artisan stamp
npm run test-saucelabs
kill %1 %2
```
