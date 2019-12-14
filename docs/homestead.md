# Homestead Documentation for Abhayagiri Website

[Laravel Homestead](https://laravel.com/docs/5.8/homestead) allows you to
quickly setup a local development environment running the Abhayagiri website.

Homestead depends on [VirtualBox](https://www.virtualbox.org/) and
[Vagrant](https://www.vagrantup.com/).

## Install Homestead Dependencies

The following commands for installing the dependencies are for 64-bit Ubuntu
(16.04+) or Debian (Stretch+) but the basic process can be used for other
systems including Mac OS X and Windows.

```sh
# Install Composer, Git and other dependencies
sudo apt update
sudo apt install -y composer git lsb-release php-curl php-xml php-zip wget

# Install VirtualBox
wget -q -O- https://www.virtualbox.org/download/oracle_vbox_2016.asc | sudo apt-key add -
echo "deb [arch=amd64] https://download.virtualbox.org/virtualbox/debian $(lsb_release -cs) contrib" | \
    sudo tee /etc/apt/sources.list.d/oracle-virtualbox.list > /dev/null
sudo apt update
sudo apt install -y virtualbox-6.0

# Install Vagrant
VAGRANT_VERSION=2.2.6
VAGRANT_DEB="$(mktemp --suffix .deb)"
chmod 644 "$VAGRANT_DEB"
wget -q -O "$VAGRANT_DEB" \
    "https://releases.hashicorp.com/vagrant/${VAGRANT_VERSION}/vagrant_${VAGRANT_VERSION}_x86_64.deb"
sudo apt install -y "$VAGRANT_DEB"
rm -f "$VAGRANT_DEB"
```

## Download and Setup Repository

```sh
cd "$HOME"
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
cp .env.example .env
composer install
cp Homestead.yaml.example Homestead.yaml
# Edit Homestead.yaml as needed
vagrant up
```

After everything installs, you should be able to browse to the development
server from your host machine: http://abhayagiri.local/

You can log in the guest machine with:

```sh
vagrant ssh
```

Once inside the guest machine, you should be able to run all the tests:

```sh
cd abhayagiri && php artisan test
```
