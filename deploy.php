<?php

require 'recipe/common.php';

set('repository', 'git@gitlab.com:abhayagiri/www.abhayagiri.org.git');
set('shared_files', [
    'config/config.php',
]);
set('shared_dirs', [
    'public/ai-cache',
    'public/media',
    'tmp',
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

task('import-test-db', function() {
    writeln('hi');
    $stage = input()->getArgument('stage');
    if ($stage == 'production') {
        throw new Exception('Not to be run on production');
    }
    run('cd {{deploy_path}}/current && php util/import-test-db.php');
    return;
})->desc('Import test database')
  ->onlyOn('local', 'staging');

task('deploy-import', [
    'deploy',
    'import-test-db'
])->desc('Deploy and import data');

?>
