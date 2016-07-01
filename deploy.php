<?php

require 'recipe/common.php';

set('repository', 'git@gitlab.com:abhayagiri/www.abhayagiri.org.git');
set('shared_files', [
    'config/config.php',
]);
set('shared_dirs', [
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
])->desc('Deploy your project');

after('deploy', 'success');

task('import_test_db', function() {
    run('php util/import-test-db.php');
    return;
})->desc('Import test database');

?>
