# Local Development

## Linux (Debian/Ubuntu)

```sh
sudo apt-get install -y git
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
scripts/local/install-debian-ubuntu-dependencies.sh
scripts/local/setup-databases.sh
scripts/install-local.sh
php artisan migrate:fresh --seed
```

## Mac OS X

Install [Homebrew](http://brew.sh/). Then:

```sh
brew install git
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
scripts/local/install-macosx-dependencies.sh
scripts/local/setup-databases.sh
scripts/install-local.sh
php artisan migrate:fresh --seed
```

## Windows

***Note: The following is outdated.***

Install Git, MariaDB 10.3, NodeJS, 7-Zip, Google Chrome, PHP 7.3 and Composer.

The following example uses [Chocolatey](https://chocolatey.org/install) but you can use whatever installer tool you like.

```powershell
choco install -y git mariadb nodejs 7zip googlechrome
choco install -y mariadb --version 10.3.15 # As of 2019-06-16
choco install -y php --version 7.3.6 # As of 2019-06-16
choco pin add --name=mariadb
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
