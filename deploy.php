<?php

namespace Deployer;

use Dotenv\Dotenv;

require 'recipe/common.php';

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
// Project name
set('staging_path', getenv('STAGING_PATH'));

// Shared files/dirs between deploys
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('api-tramatic')
    ->stage('staging')
    ->set('http_user', getenv('HTTP_USER'))
    ->set('deploy_path', '{{staging_path}}');

task('upload', function () {
    upload(__DIR__ . DIRECTORY_SEPARATOR, '{{release_path}}');
});

// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:writable',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
