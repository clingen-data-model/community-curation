<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'community-curation');

// Project repository
set('repository', 'git@bitbucket.org:shepsweb/community-curation.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('freshseed', function () {
    run('cd {{release_path}} && php artisan migrate:fresh --seed');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

host('test')
    ->hostname('web3demo.schsr.unc.edu')
    ->stage('test')
    ->set('branch', 'test')
    ->set('deploy_path', '/mnt/web/project/{{application}}-test')
    ->roles(['test', 'internal', 'stage']);

host('demo')
    ->hostname('web3demo.schsr.unc.edu')
    ->stage('demo')
    ->set('branch', 'demo')
    ->set('deploy_path', '/mnt/web/project/{{application}}')
    ->roles(['demo', 'client', 'stage']);

// host('prod')
//     ->hostname('web3.schsr.unc.edu')
//     ->stage('production')
//     ->set('branch', 'master')
//     ->set('deploy_path', '/mnt/web/project/{{application}}')
//     ->roles(['production', 'client', 'stage']);

task('artisan:optimize', function () {
});
