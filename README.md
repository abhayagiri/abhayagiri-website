# Abhayagiri Website

## Quickstart

Install [Git](https://git-scm.com/downloads) and [Docker](README.docker.md) (if needed). Then:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
cp docker/docker-compose.yml .
docker-compose up
```

Point your browser to http://localhost/ or the IP provided by `docker-machine ip`.

To login to Mahapanel, go to: http://localhost/mahapanel_bypass?email=root@localhost

## Test

Open a shell inside the web container:

```
docker-compose exec web app
```

Then run the tests:

```
vendor/bin/codecept run
```

## More

- [Local Setup](README.Setup.md)
- [Other Docker Setup Options](README.Docker.md)
- [Server Deployment](README.Deploy.md)
