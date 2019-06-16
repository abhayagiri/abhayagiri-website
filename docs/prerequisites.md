# Prerequisites

## Linux (Debian Stretch/Buster, Ubuntu 16.04+)

Install Git, MariaDB, NodeJS, Google Chrome, PHP 7.3 and Composer:

```sh
wget -O - -q https://deb.nodesource.com/setup_12.x | sudo -E bash -
sudo apt-get install -y \
  apt-transport-https lsb-release ca-certificates \
  git mariadb-client mariadb-server nodejs

# Install Google Chrome
wget -O - -q https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
sudo bash -c 'echo "deb http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list'
sudo apt-get update
sudo apt-get install -y google-chrome-stable

# Install PHP 7.3
wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
sudo bash -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
sudo apt-get update
sudo apt-get install -y \
  php7.3 php7.3-bz2 php7.3-curl php7.3-gd php7.3-opcache \
  php7.3-mbstring php7.3-mysql php7.3-xml php7.3-zip

# Install Composer
wget -O - -q https://getcomposer.org/installer | sudo php7.3 -- --install-dir=/usr/local/bin --filename=composer
```

If you have multiple PHP versions (you can tell by doing `ls /usr/bin/php*`),
you'll need to specify the PHP version:

```sh
sudo update-alternatives --set php /usr/bin/php7.3
sudo update-alternatives --set phar /usr/bin/phar7.3
sudo update-alternatives --set phar.phar /usr/bin/phar.phar7.3
```

## Mac OS X

Install [Homebrew](http://brew.sh/).

Install Git, MySQL, NodeJS, Google Chrome, PHP 7.3 and Composer:

```sh
brew tap homebrew/homebrew-php
brew install git mysql node php73 composer
brew cask install google-chrome
brew services start mysql
```

## Windows

Install Git, MariaDB, NodeJS, 7-Zip, Google Chrome, PHP 7.3 and Composer.

The following example uses [Chocolatey](https://chocolatey.org/install) but you can use whatever installer tool you like.

```powershell
choco install -y git mariadb nodejs 7zip googlechrome
choco install -y php --version 7.3.6
choco pin add --name=php
choco install -y composer
```

The Chocolatey PHP installer doesn't seem to set `PATH` correctly. If this is the case, run this in an elevated PowerShell shell:

```powershell
$SystemPath = [Environment]::GetEnvironmentVariable("PATH", "Machine")
If (-Not $SystemPath.Contains('C:\tools\php73')) {
  $SystemPath = $SystemPath + ";C:\tools\php73"
  [Environment]::SetEnvironmentVariable("PATH", $SystemPath, "Machine")
}
```

The Chocolatey PHP installer also does not enable some extensions by default. You will want to make sure that the `bz2`, `curl`, `gd2`, `mbstring`, `openssl`, `pdo_mysql` extensions are enabled:

```powershell
$Config = Get-Content -Path 'C:\tools\php73\php.ini'
ForEach ($Ext in @('bz2', 'curl', 'gd2', 'mbstring', 'openssl', 'pdo_mysql')) {
  $Config = $Config -Replace ";(extension=php_$($Ext)\.dll)", '$1'
}
Echo $Config | Set-Content -Path 'C:\tools\php73\php.ini'
```
