#!/bin/bash
#
# Install/Update Packages for Abhayagiri Website
#
# https://github.com/abhayagiri/abhayagiri-website/blob/dev/scripts/forge/recipes/install-update-packages.sh
#

set -e

log="$(mktemp /tmp/install-update-packages-$(date +%Y%m%d-%H%M%S)-XXXXXX.log)"

chmod 644 "$log"

(
    set -x
    cd /
    export DEBIAN_FRONTEND=noninteractive

    apt-get update

    apt-get upgrade -y \
        -o "Dpkg::Options::=--force-confdef" \
        -o "Dpkg::Options::=--force-confold"

    apt-get dist-upgrade -y \
        -o "Dpkg::Options::=--force-confdef" \
        -o "Dpkg::Options::=--force-confold"

    # Install PHP dependencies
    apt-get install -y \
        php7.3 php7.3-bz2 php7.3-curl php7.3-gd php7.3-opcache \
        php7.3-mbstring php7.3-mysql php7.3-xml php7.3-zip

    # Install rclone
    ( curl https://rclone.org/install.sh | bash ) || true

    # Cleanup
    apt-get autoremove -y
    apt-get clean
    apt-get autoclean

) > "$log" 2>&1
