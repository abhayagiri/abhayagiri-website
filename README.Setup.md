
# Setup

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
