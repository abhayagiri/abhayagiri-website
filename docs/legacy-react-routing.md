As of June 22, 2018, the website still uses a mixture of old legacy PHP/Ajax for
routing as well the newer React-style routing. Here are the places you need to
modify when adding/changing a top level route:

1.  Around line 428 in `public/.htaccess`.
2.  Line 15 via `readyPrefixes` in `webpack.config.js`.
3.  Line 6 via `readyPrefixes` in `public/js/main.js`.
4.  The route via `localizeRoutes` in `new/app.js`.
5.  The entry in `new/data/pages.json`.
