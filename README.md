# Abhayagiri Website

## Quickstart

Install [preliminaries](README.Setup.md), then:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
./first-time-setup
php -S localhost:8000 -t public
```

Then, point your browser to http://localhost:8000/.

To login to Mahapanel, go to: http://localhost:8000/mahapanel_bypass?email=root@localhost

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
