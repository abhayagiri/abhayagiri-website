# Nginx Configuration for Abhayagiri Website

## Example Configuration

Here's an example Nginx configuration:

```
server {
  listen 80 default_server;
  listen [::]:80 default_server;
  server_name _;
  return 302 https://<domain>$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name <domain>;
    root <path to>/public;

    # HTTPS
    ssl_certificate <path to>/fullchain.pem;
    ssl_certificate_key <path to>/key.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    error_page 404 /index.php;

    # BEGIN Extra Abhayagiri Nginx Configuration
    # Handle /th
    rewrite ^/th/?$ /index.php last;
    # Do not allow PHP under /media
    location ~ ^/media/.*\.phps?$ { deny all; }
    # Redirect /new/talks to /talks
    rewrite ^/new/(th/)?talks(.*)$ https://$server_name/$1talks$2 redirect;
    # React routes
    rewrite ^/(th/)?(gallery|talks|contact)(/.*)?$ /new/index.html last;
    # END Extra Abhayagiri Nginx Configuration

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /index.php {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
        include snippets/fastcgi-php.conf;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Replace `<domain>` and `<path to`> as appropriate.
