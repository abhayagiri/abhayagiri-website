Some notes on running `nightwatch` for development:

Run all tests in one test file:

```sh
npm run nightwatch -- -t tests/nightwatch/tests/<file>.js
```

Run a single test in one test file:

```sh
npm run nightwatch -- -t tests/nightwatch/tests/<file>.js --testcase 'my test'
```
