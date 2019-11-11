#!/bin/sh
#
# Homestead post-provisioning setup script
#

# Restart avahi to ensure abhayagiri.local name is resolved
sudo systemctl restart avahi-daemon.service

# Ensure everything is upgraded
sudo apt-get update
DEBIAN_FRONTEND=noninteractive sudo -E apt-get dist-upgrade -y \
    -o "Dpkg::Options::=--force-confdef" \
    -o "Dpkg::Options::=--force-confold"

# Install required packages
DEBIAN_FRONTEND=noninteractive sudo -E apt-get install -y \
    php7.3-bz2 zip

# Install Google Chrome
wget -O - -q https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
sudo bash -c 'echo "deb http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list'
sudo apt-get update
sudo apt-get install -y google-chrome-stable

# Set custom nginx configuration to handle legacy and React routing
cat <<'EOF' | sudo tee /etc/nginx/sites-available/abhayagiri.local > /dev/null
server {
    listen 80;
    listen 443 ssl http2;
    server_name .abhayagiri.local;
    root "/home/vagrant/abhayagiri/public";

    index index.html index.htm index.php;

    charset utf-8;

    # Handle /th
    rewrite ^/th/?$ /index.php last;
    # Do not allow PHP under /media
    location ~ ^/media/.*\.phps?$ { deny all; }
    # Redirect /new/talks to /talks
    rewrite ^/new/(th/)?talks(.*)$ http://$server_name/$1talks$2 redirect;
    # React routes
    rewrite ^/(th/)?(gallery|talks|contact)(/.*)?$ /new/index.html last;

    # Proxy 20th Anniversary to DigitalOcean Spaces
    rewrite ^/20$ /20/ redirect;
    rewrite ^/20/$ /20/index.html redirect;
    location /20/ {
        proxy_pass https://abhayagiri.sfo2.digitaloceanspaces.com/media/discs/Abhayagiri%27s%2020th%20Anniversary/;
    }

    # Proxy /media to DigitalOcean Spaces
    rewrite ^/(media/.+)/$ https://$server_name/$1/index.html redirect;
    location /media/ {
        proxy_pass https://abhayagiri.sfo2.digitaloceanspaces.com/media/;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log  /var/log/nginx/abhayagiri.local-error.log error;

    sendfile off;

    client_max_body_size 100m;

    location /index.php {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }

    ssl_certificate     /etc/nginx/ssl/abhayagiri.local.crt;
    ssl_certificate_key /etc/nginx/ssl/abhayagiri.local.key;
}
EOF
sudo systemctl restart nginx.service

cd /home/vagrant/abhayagiri

if ! test -f .env; then
    cp .env.example .env
fi

# Use abhayagiri.local as URL
perl -pi -e 's/^APP_URL=.*$/APP_URL=http:\/\/abhayagiri.local/' .env

composer install

php artisan key:generate
php artisan dusk:chrome-driver
php artisan app:import-media
php artisan app:import-database
npm install && npm run build
( cd mix && npm install && npm run production )
