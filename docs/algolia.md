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

## Commands

### Create Indexes (first use)

```sh
php artisan scout:import
```

### Updating Indexes (subsequent use)

```sh
php artisan scout:reimport
```

### Update Configuration

```sh
php artisan scout:sync -n
```

### Get Status

```sh
php artisan scout:status
```
