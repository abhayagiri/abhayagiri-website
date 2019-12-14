# Algolia Documentation for Abhayagiri Website

[Algolia](https://www.algolia.com/) is a SaaS search provider used by the
Abhaygiri Website to provide end-user search results.

The account dashboard is accessible through https://www.algolia.com/dashboard.

## Create an Application

Go to the [Algolia applications
page](https://www.algolia.com/account/applications) and click **New
application**. Fill out the fields and click **Create**.

Then, go to **API Keys** and copy the keys into the `.env` file:

```
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=...
ALGOLIA_SECRET=...
ALGOLIA_SEARCH=...
```

Note: `ALGOLIA_SECRET` refers to the **Admin API Key**.

## Create Indexes (first use)

```sh
php artisan scout:import
```

## Updating Indexes (subsequent use)

```sh
php artisan scout:reimport
```

## Update settings

```sh
php artisan scout:sync -n
```

## Get Status

```sh
php artisan scout:status
```

## General Notes

- Algolia offers limited record and operation quotas so one needs to be
  conservative with how often one does re-indexing during development. It might
  be useful to have a test flag to limit the number of search index records
  produced so as to be able to test but not blow through the quota.

- As of 2019-12-10, we're using a customized version of algolia/scout located
  at https://github.com/abhayagiri/scout-extended. There is currently a pull
  request to incorporate those changes upstream.
