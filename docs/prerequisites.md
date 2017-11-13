# Prerequisites

## Linux (Debian Stretch)

Install Git, MariaDB, NodeJS, PHP 7.0 and Composer:

```sh
sudo apt-get install -y curl
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y git mariadb-client mariadb-server nodejs \
  php7.0 php7.0-bz2 php7.0-curl php7.0-gd php7.0-opcache \
  php7.0-mbstring php7.0-mysql php7.0-xml php7.0-zip
if ! test -f /usr/local/bin/composer; then
  curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
fi
```

## Mac OS X

Install [Homebrew](http://brew.sh/).

Install Git, MySQL, NodeJS, PHP 7.0 and Composer:

```sh
brew tap homebrew/homebrew-php
brew install git mysql node php70 composer
brew services start mysql
```

## Windows

Install Git, MariaDB, NodeJS, PHP 7.0, Composer and 7-Zip.

The following example uses [Chocolatey](https://chocolatey.org/install) but you can use whatever installer tool you like.

```powershell
choco install -y git mariadb nodejs 7zip
choco install -y php --version 7.0.20
choco pin add --name=php
choco install -y composer
```

The Chocolatey PHP installer doesn't seem to set `PATH` correctly. If this is the case, run this in an elevated PowerShell shell:

```powershell
$SystemPath = [Environment]::GetEnvironmentVariable("PATH", "Machine")
If (-Not $SystemPath.Contains('C:\tools\php70')) {
  $SystemPath = $SystemPath + ";C:\tools\php70"
  [Environment]::SetEnvironmentVariable("PATH", $SystemPath, "Machine")
}
```

The Chocolatey PHP installer also does not enable some extensions by default. You will want to make sure that the `bz2`, `curl`, `gd2`, `mbstring`, `openssl`, `pdo_mysql` extensions are enabled:

```powershell
$Config = Get-Content -Path 'C:\tools\php70\php.ini'
ForEach ($Ext in @('bz2', 'curl', 'gd2', 'mbstring', 'openssl', 'pdo_mysql')) {
  $Config = $Config -Replace ";(extension=php_$($Ext)\.dll)", '$1'
}
Echo $Config | Set-Content -Path 'C:\tools\php70\php.ini'
```
