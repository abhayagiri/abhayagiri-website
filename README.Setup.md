
# Additional Setup

## Prerequisites

### Linux

```sh
sudo apt-get install -y curl
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y nodejs
sudo apt-get install -y apache2 git mysql-client mysql-server \
  php7.0 php7.0-bz2 php7.0-curl php7.0-gd php7.0-opcache \
  php7.0-mbstring php7.0-mysql php7.0-xml php7.0-zip
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### OS X

Install [Homebrew](http://brew.sh/):

```sh
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

Install Git, Apache, PHP, MySQL and composer:

```sh
brew tap homebrew/dupes
brew tap homebrew/versions
brew tap homebrew/apache
brew tap homebrew/homebrew-php
brew install git httpd24 mysql php70
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
brew services start mysql
brew services start httpd24
```

Port forward 80 to 8080:

```sh
echo "
rdr pass inet proto tcp from any to any port 80 -> 127.0.0.1 port 8080
" | sudo pfctl -ef -
```

## DNS Resolution

Edit `/etc/hosts`:

```
127.0.0.1 web db web.abhayagiri.dev db.abhayagiri.dev
```

## Apache Configuration

### Linux

TODO

### OS X

Edit `/usr/local/etc/apache2/2.4/httpd.conf`:

```
...
LoadModule rewrite_module libexec/mod_rewrite.so
LoadModule php7_module /usr/local/opt/php70/libexec/apache2/libphp7.so
...
<VirtualHost *:8080>
  ServerName web.abhayagiri.dev
  DocumentRoot /path/to/website/public
  <Directory /path/to/website/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>
```

Restart apache:

```sh
brew services restart httpd24
```

## Download and configure

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
php first-time-setup
```

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
