To install:

```sh
sudo adduser deployer
sudo apt-get install -y php7.0-fpm
sudo systemctl stop nginx
sudo acme.sh --issue --standalone -d deploy.abhayagiri.org
sudo mkdir -p /etc/nginx/certs/deploy.abhayagiri.org
sudo chmod 700 /etc/nginx/certs/deploy.abhayagiri.org
sudo systemctl start nginx
sudo acme.sh --install-cert -d deploy.abhayagiri.org \
    --key-file       /etc/nginx/certs/deploy.abhayagiri.org/key \
    --fullchain-file /etc/nginx/certs/deploy.abhayagiri.org/fullchain \
    --reloadcmd      "systemctl reload nginx"
cat <<EOF sudo tee -a /etc/nginx/sites-available/deploy > /dev/null
server {
    listen 80;
    listen [::]:80;
    server_name deploy.abhayagiri.org;
    return 301 https://deploy.abhayagiri.org\$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name deploy.abhayagiri.org;
    ssl_certificate /etc/nginx/certs/deploy.abhayagiri.org/fullchain;
    ssl_certificate_key /etc/nginx/certs/deploy.abhayagiri.org/key;
    root /opt/abhayagiri-website/deployer/public;
    index index.php index.html index.htm;
    location / {
        try_files \$uri \$uri/ =404;
    }
    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
    }
}
EOF
sudo ln -s ../sites-available/deploy /etc/nginx/sites-enabled/deploy
sudo systemctl reload nginx
sudo mkdir /opt/abhayagiri-website
sudo chown deployer:deployer /opt/abhayagiri-website
sudo -u deployer git clone https://github.com/abhayagiri/abhayagiri-website /opt/abhayagiri-website
sudo chmod 777 /opt/abhayagiri-website/deployer/builds
sudo chmod 666 /opt/abhayagiri-website/deployer/.lock
```
