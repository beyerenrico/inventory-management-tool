<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/deploy/env.php';

// Config

set('repository', 'https://github.com/beyerenrico/inventory-management-tool.git');
set('env_file', __DIR__ . '/.env.production');

add('shared_files', ['.env']);
add('shared_dirs', ['storage']);
add('writable_dirs', ['storage', 'bootstrap/cache']);


// Hosts

host(getenv('DEPLOY_HOST') ?: 'inv.beyerenrico.com')
    ->set('remote_user', getenv('DEPLOY_USER') ?: 'deployer')
    ->set('deploy_path', getenv('DEPLOY_PATH') ?: '~/deployments/inventory-management-tool')
    ->set('sudo_password', getenv('DEPLOY_SUDO_PASSWORD'))
    ->set('domain', getenv('DEPLOY_DOMAIN') ?: 'inv.beyerenrico.com')
    ->set('public_path', 'public')
    ->set('php_version', '8.3')
    ->set('db_type', 'mariadb')
    ->set('db_user', getenv('DEPLOY_DB_USER') ?: 'deployer')
    ->set('db_name', getenv('DEPLOY_DB_NAME') ?: 'prod')
    ->set('db_password', getenv('DEPLOY_DB_PASSWORD'));

// Hooks

before('deploy:vendors', 'deploy:env');
after('deploy:failed', 'deploy:unlock');
