# Abhayagiri Website

## Quickstart

Install [preliminaries](README.Setup.md), then:

```sh
# Preliminaries
git clone https://github.com/abhayagiri/abhayagiri-website
./first-time-setup
```

Point your browser to http://localhost/ or the IP provided by `docker-machine ip`.

To login to Mahapanel, go to: http://localhost/mahapanel_bypass?email=root@localhost

## Test

[In a shell](README.Docker.md#shell):

```
vendor/bin/codecept run
```

## More

- [Local Setup](README.Setup.md)
- [Other Docker Setup Options](README.Docker.md)
- [Server Deployment](README.Deploy.md)
- [Google OAuth Setup](README.Setup.md#google-oauth)
