#!/bin/bash
#
# Setup Local (Dev/Test) MySQL Databases for Abhyagiri Website
#

set -e
cd "$(dirname "$0")/../.."

LOCAL_DB_NAME="abhayagiri"
DUSK_DB_NAME="abhayagiri_dusk"

cat <<EOF | sudo mysql -u root
CREATE DATABASE IF NOT EXISTS \`$LOCAL_DB_NAME\`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS \`$DUSK_DB_NAME\`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL ON \`$LOCAL_DB_NAME\`.* TO '$LOCAL_DB_NAME'@'localhost'
    IDENTIFIED BY '$LOCAL_DB_NAME';

GRANT ALL ON \`$DUSK_DB_NAME\`.* TO '$DUSK_DB_NAME'@'localhost'
    IDENTIFIED BY '$DUSK_DB_NAME';

FLUSH PRIVILEGES;
EOF
