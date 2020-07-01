#!/bin/bash
#
# Setup Local (Dev/Test) MySQL Databases for Abhyagiri Website
#

set -e
cd "$(dirname "$0")/../.."

LOCAL_DB_NAME="abhayagiri"
TEST_DB_NAME="abhayagiri_test"

cat <<EOF | sudo mysql -u root
CREATE DATABASE IF NOT EXISTS \`$LOCAL_DB_NAME\`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS \`$TEST_DB_NAME\`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL ON \`$LOCAL_DB_NAME\`.* TO '$LOCAL_DB_NAME'@'localhost'
    IDENTIFIED BY '$LOCAL_DB_NAME';

GRANT ALL ON \`$TEST_DB_NAME\`.* TO '$TEST_DB_NAME'@'localhost'
    IDENTIFIED BY '$TEST_DB_NAME';

FLUSH PRIVILEGES;
EOF
