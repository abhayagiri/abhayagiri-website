# Docker for abhayagiri-website

## Install

- [Docker for Windows](https://docs.docker.com/engine/installation/windows/)
- [Docker for Mac](https://docs.docker.com/engine/installation/mac/)
- [Docker for Linux](https://docs.docker.com/engine/installation/linux/)

For older systems, [install `docker-machine`](#docker-machine).

## Configure

```sh
cp docker/docker-compose.yml .
```

## Run

```sh
docker-compose up -d
```

## Shell

```
docker-compose exec web app
```

## Docker Machine

Older versions of Windows (pre Windows 10) and OS X (hardware before 2010) will need to use the `docker-machine` instead of Docker for Windows/Mac. You can get `docker-machine` from either [Docker Toolbox](https://www.docker.com/products/docker-toolbox) or one of the following methods:

### Windows with Chocolatey

Install [Chocolatey](https://github.com/chocolatey/choco/wiki/Installation) (if needed). Then:

```
choco install -y docker docker-compose docker-machine virtualbox
docker-machine create --driver virtualbox default
```

For each shell using Docker, you will need to initialize the environment:

```
docker-machine env --shell powershell | iex
```

### OS X with Homebrew

Install [Homebrew](http://brew.sh/) (if needed). Then:

```sh
brew cask install virtualbox
brew install docker docker-compose docker-machine
docker-machine create --driver virtualbox default
```

For each shell using Docker, you will need to initialize the environment:

```
eval $(docker-machine env)
```

## Docker Sync

Systems using `docker-machine` may experience slow performance using standard shared folders. One option for OS X is to use [docker-sync](http://docker-sync.io/).

### Install

[Install Ruby](https://gorails.com/setup/osx) (if needed). Then:

```sh
brew install fswatch rsync unison
gem install docker-sync
```

### Configure

```sh
cp docker/docker-sync/docker-compose.yml .
sed "s/DOCKER_MACHINE_IP/`docker-machine ip`/" docker/docker-sync/docker-sync.yml > docker-sync.yml
```

### Run

```sh
docker-sync-stack start
```
