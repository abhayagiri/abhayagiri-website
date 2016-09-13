
# Additional Setup

## Prerequisites on Linux

```
apt-get install -y git apache2 php5 mysql-client mysql-server
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

## Prerequisites on OS X

Install [Homebrew](http://brew.sh/) (if needed):

```
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

Install Git, PHP, MySQL and composer:

```
brew tap homebrew/dupes
brew tap homebrew/versions
brew tap homebrew/homebrew-php
brew install git mysql php56
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
brew services start mysql
```

## Download and configure

```
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
php first-time-setup
```

Setup Apache:

- Enable PHP
- Enable rewrite
- Point `DocumentRoot` to the `public` directory
- Add `AllowOverride All` to the `public` directory

## Google OAuth

Go to https://console.developers.google.com/apis/

- Click **Create a Project**
  - Fill in a name
  - Click **Create**
- Select the newly created project (if not already done so)
- Click **Enable API**
- Click **Credentials** on the left pane
- Click **OAuth consent screen**
  - Fill in email, name, and anything else
  - Click **Save**
- Click **Create Credentials**, then **OAuth client ID**
  - Choose **Web Application**
  - Fill in name
  - For **Authorized JavaScript origins**, put in the base website URL
    - e.g., https://myhost
  - For **Authorized Redirect URIs**, put in the base website URL + `mahapanel/login`
    - e.g., https://myhost/mahapanel/login
  - Click Save

**Client ID** and **Client secret** can be copied to `AUTH_GOOGLE_CLIENT_ID` and `AUTH_GOOGLE_CLIENT_SECRET` in `.env`.
