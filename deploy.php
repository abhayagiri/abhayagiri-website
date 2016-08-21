<?php

namespace Deployer;

require 'recipe/common.php';

set('repository', 'git@gitlab.com:abhayagiri/www.abhayagiri.org.git');
set('shared_files', [
    '.env',
]);
set('shared_dirs', [
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'storage/tmp',
    'public/ai-cache',
    'public/media',
]);
set('writable_dirs', []);

localServer('local')
    ->stage('local')
    ->env('deploy_path', __DIR__ . '/local-deploy');

server('staging', 'twokilts.dreamhost.com', 22)
    ->user('abhayagiri_staging')
    ->identityFile()
    ->stage('staging')
    ->env('deploy_path', '/home/abhayagiri_staging/staging.abhayagiri.org');

server('production', 'twokilts.dreamhost.com', 22)
    ->user('abhayagiri')
    ->identityFile()
    ->stage('production')
    ->env('deploy_path', '/home/abhayagiri/www.abhayagiri.org');

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy');

after('deploy', 'success');

task('deploy:restart-php-processes', function() {
    run('killall php56.cgi || true');
})->desc('Restart PHP processes');

after('deploy:symlink', 'deploy:restart-php-processes');

task('deploy:migrate-db', function() {
    run('cd {{deploy_path}}/current && php artisan migrate');
})->desc('Run database migrations');

after('deploy:symlink', 'deploy:migrate-db');

task('deploy:import-test-db', function() {
    $stage = input()->getArgument('stage');
    if ($stage == 'production') {
        throw new Exception('Not to be run on production');
    }
    run('cd {{deploy_path}}/current && php artisan command:import-test-db');
})->desc('Import test database')
  ->onlyOn('local', 'staging');

task('deploy-and-import', [
    'deploy',
    'deploy:import-test-db',
    'deploy:migrate-db',
])->desc('Deploy and import data');

?>
