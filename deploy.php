<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'BaseCRM');
set('writable_mode', 'chown');
set('http_user', 'baseqigg');
set('keep_releases', '1');

// Project repository
set('repository', 'https://github.com/mchitaru/basecrm.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('basecrm.io')
    ->set('deploy_path', '/home/baseqigg/deployment');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

