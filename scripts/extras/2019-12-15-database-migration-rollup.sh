#!/bin/bash
#
# This script is to handle the migrations cleanup (issue #112) that occured on
# 2019-12-15.
#
# This script was used in the context of new Laravel project:
#
# 1. A new Laravel 5.8 project was created called `blah`.
# 2. A local database was created called `blah` with username/password `blah`.
# 3. The `.env` is modified to use the above database.
# 4. The following dependency was added:
#
#     composer require xethron/migrations-generator '^2.0'
#
# 5. A dump of the production database was copied to `database/abhayagiri.sql`.

set -e

db="blah"
creds="-u $db -p$db $db"

################
# Prepare Test #
################

mysql $creds -e "DROP DATABASE $db; CREATE DATABASE $db;"

mysql $creds < database/abhayagiri.sql

# The following are to be run on the production database at some point in the
# future.  This is to tidy/clean-up things that should have minimal impact.

cat <<'EOF' | php artisan tinker
DB::statement("ALTER TABLE `migrations` ADD COLUMN `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST;");
DB::statement("ALTER TABLE `failed_jobs` MODIFY `connection` text NOT NULL;");
DB::statement("ALTER TABLE `failed_jobs` MODIFY `queue` text NOT NULL;");
DB::statement("ALTER TABLE `talks` MODIFY created_at timestamp NULL DEFAULT NULL;");
DB::statement("ALTER TABLE `talks` MODIFY updated_at timestamp NULL DEFAULT NULL;");
foreach (['authors', 'books', 'playlists', 'subject_groups', 'subjects', 'tags', 'talks', 'users'] as $table) {
    DB::statement("ALTER TABLE `$table` MODIFY deleted_at timestamp NULL DEFAULT NULL;");
}
exit
EOF

# This result will be used later for testing...

mysqldump $creds -d > database/a.sql

###########################
# Start Create Migrations #
###########################

# Remove existing migrations

rm -f database/migrations/*

# Use the following migration template

cat <<'EOF' > database/generate-template.txt
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class $CLASS$ extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $UP$
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $DOWN$
    }
}
EOF

# Generate the migrations

php artisan migrate:generate --quiet --no-interaction\
    --templatePath=database/generate-template.txt \
    --ignore=cache,failed_jobs,jobs,migrations,revisions,sessions,sync_tasks,sync_task_logs

# Apply specific fixes to the newly created migrations

perl -pi -e "s/youtube_video_id'\)/youtube_video_id')->collation('utf8mb4_bin')/" database/migrations/*_create_talks_table.php
perl -pi -e "s/youtube_playlist_id'\)/youtube_playlist_id')->collation('utf8mb4_bin')/" database/migrations/*_create_playlists_table.php
for m in database/migrations/*.php; do
    perl -pi -e 's/\t/    /g' "$m"
    perl -pi -e 's/>text\((.+), 65535/>text(\1/g' "$m"
    perl -pi -e 's/>text\((.+), 16777215/>mediumtext(\1/g' "$m"
    perl -pi -e 's/->index\(.+?\)//g' "$m"
    perl -pi -e "s/Schema::/if (app()->env === 'production') return; \\/\\/ SKIP ON PRODUCTION\\n        Schema::/" "$m"
done

# Download select existing migrations

giturl_prefix="https://raw.githubusercontent.com/abhayagiri/abhayagiri-website/c4feaaa38b2289b6a4ae640f21ff5807cce81ec6/database/migrations"
for m in \
        2017_07_06_031704_create_jobs_table.php \
        2017_07_06_140040_create_failed_jobs_table.php \
        2017_09_09_062329_create_revisions_table.php \
        2019_06_27_021759_create_cache_table.php \
        2019_06_27_021944_create_sessions_table.php \
        2019_06_27_030000_create_sync_task_tables.php; do
    wget -q -O "database/migrations/$m" "$giturl_prefix/$m"
done

# Fix a couple problems with existing migrations

cat <<'EOF' | patch -p1
--- old/database/migrations/2017_09_09_062329_create_revisions_table.php   2019-12-06 02:42:43.693240781 +0000
+++ new/database/migrations/2017_09_09_062329_create_revisions_table.php   2019-12-15 20:47:47.809658883 +0000
@@ -17,8 +17,8 @@
             $table->integer('revisionable_id');
             $table->integer('user_id')->nullable();
             $table->string('key');
-            $table->text('old_value')->nullable();
-            $table->text('new_value')->nullable();
+            $table->mediumtext('old_value')->nullable();
+            $table->mediumtext('new_value')->nullable();
             $table->timestamps();
 
             $table->index(array('revisionable_id', 'revisionable_type'));
--- old/database/migrations/2019_06_27_030000_create_sync_task_tables.php  2019-12-06 02:42:43.693240781 +0000
+++ new/database/migrations/2019_06_27_030000_create_sync_task_tables.php  2019-12-15 21:36:25.535732030 +0000
@@ -28,7 +28,9 @@
         Schema::create("sync_task_logs", function (Blueprint $table) {
             $table->increments('id');
             $table->unsignedInteger('sync_task_id');
-            $table->text('log')->default('');
+            // TEXT column on MySQL 5.7 can't have a default value.
+            // ERROR: Syntax error or access violation: 1101
+            $table->text('log');
             $table->timestamps(6); // microsecond support
             $table->foreign('sync_task_id')->references('id')->on('sync_tasks')
                   ->onUpdate('CASCADE')->onDelete('CASCADE');
EOF

#########################
# END Create Migrations #
#########################

#########
# Test! #
#########

mysql $creds -e "DROP DATABASE $db; CREATE DATABASE $db;"

php artisan migrate

mysqldump $creds -d > database/b.sql

perl -pi -e 's/ AUTO_INCREMENT=[0-9]*//g' database/a.sql
perl -pi -e 's/ AUTO_INCREMENT=[0-9]*//g' database/b.sql

diff -u database/a.sql database/b.sql || true
