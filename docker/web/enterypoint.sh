#!/bin/bash

getent group "$APP_GID" > /dev/null
if [ $? != 0 ]; then
    echo "Creating group $APP_GROUP ($APP_GID)"
    groupadd --gid $APP_GID "$APP_GROUP"
fi

getent passwd "$APP_UID" > /dev/null
if [ $? != 0 ]; then
    echo "Creating user $APP_USER ($APP_UID)"
    APP_HOME="/home/$APP_USER"
    useradd --create-home --home-dir "$APP_HOME" \
        --shell /bin/bash \
        --uid=$APP_UID --gid=$APP_GID "$APP_USER"
    cat <<-EOF >> "$APP_HOME/.profile"

export COMPOSER_CACHE_DIR="$COMPOSER_CACHE_DIR"
EOF
fi

cat <<-EOF > /etc/apache2/conf-available/user.conf
User $APP_USER
Group $APP_GROUP
EOF

chown -R "$APP_USER:$APP_GROUP" /var/{lock,log,run}/apache*

if [ ! -f /first-time-setup ]; then
    sudo -u app -i /app/first-time-setup
    touch /first-time-setup
fi

exec "$@"
