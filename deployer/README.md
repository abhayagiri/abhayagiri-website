# Automatic Deployment

## Install (on deploy.abhayagiri.org)

```sh
sudo apt-get update
sudo apt-get install -y curl
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y git mariadb-client mariadb-server nodejs \
  php7.0 php7.0-bz2 php7.0-curl php7.0-gd php7.0-opcache \
  php7.0-mbstring php7.0-mysql php7.0-xml php7.0-zip
if ! test -f /usr/local/bin/composer; then
  curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
fi
sudo apt-get install -y nginx php7.0-fpm supervisor
if ! sudo bash -c 'test -d /root/.acme.sh'; then
  curl https://get.acme.sh | sudo sh
fi
sudo systemctl stop nginx
sudo /root/.acme.sh/acme.sh --issue --standalone -d deploy.abhayagiri.org
sudo mkdir -p /etc/nginx/certs/deploy.abhayagiri.org
sudo chmod 700 /etc/nginx/certs/deploy.abhayagiri.org
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
sudo ln -sf ../sites-available/deploy /etc/nginx/sites-enabled/deploy
sudo systemctl start nginx
sudo mkdir /opt/abhayagiri-website
sudo chown www-data:www-data /opt/abhayagiri-website
sudo -u www-data git clone https://github.com/abhayagiri/abhayagiri-website /opt/abhayagiri-website
cd /opt/abhayagiri-website
for d in .composer .config .npm .ssh; do
  sudo mkdir -p /var/www/$d
  sudo chown www-data:www-data /var/www/$d
done
sudo chmod 700 /var/www/.ssh
sudo -u www-data ssh-keygen -t rsa -N '' -C '' -f /var/www/.ssh/id_rsa
echo "GRANT ALL ON abhayagiri.* TO 'abhayagiri'@'localhost' IDENTIFIED BY 'abhayagiri';FLUSH PRIVILEGES;" | sudo mysql -u root
sudo -u www-data php first-time-setup
cat <<-EOF | sudo tee -a /etc/supervisor/conf.d/abhayagiri-website-worker.conf > /dev/null
[program:abhayagiri-website-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /opt/abhayagiri-website/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/opt/abhayagiri-website/storage/logs/worker.log
EOF
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart abhayagiri-website-worker:*
```

## Upgrade (on deploy.abhayagiri.org)

```sh
cd /opt/abhayagiri-website
sudo -u www-data git pull
sudo -u www-data composer install
sudo -u www-data php artisan app:import-database
sudo supervisorctl restart abhayagiri-website-worker:*
```
