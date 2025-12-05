<?php

declare(strict_types=1);

namespace Deployer;

use Exception;

require 'recipe/symfony.php';

try {
    set('application', 'oh-backend');
    set('git_ssh_command', 'ssh -o StrictHostKeyChecking=no -i ~/.ssh/id_rsa');
    set('repository', 'https://github.com/Flaaim/oh-backend.git');
    set('php_version', '8.2');
    set('bin/php', '/opt/php/8.2/bin/php');
    set('writable_mode', 'chmod');

    host('olimpoks-help')
        ->set('hostname', '31.31.198.114')
        ->set('port', 22)
        ->set('remote_user', 'u1656040')
        ->set('deploy_path', '~/www/olimpoks-help.ru/public')
        ->set('branch', 'main');

    host('rtn-answers')
        ->set('hostname', '31.31.198.114')
        ->set('port', 22)
        ->set('remote_user', 'u1656040')
        ->set('deploy_path', '~/www/rtn-answers.ru/public')
        ->set('branch', 'main');


    set('shared_dirs', [
        'config/env',
        'public/templates',
        'var',
    ]);

    set('writable_dirs', [
        'var/log',
        'var/cache',
    ]);

    set('bin/composer', '{{bin/php}} {{deploy_path}}/composer.phar');
    set('composer_options', '--no-dev --optimize-autoloader --no-progress --no-interaction --no-scripts');

    task('check:composer', function () {
        if (!test('[ -f {{deploy_path}}/composer.phar ]')) {
            run('cd {{deploy_path}} && curl -sS https://getcomposer.org/installer | {{bin/php}}');
            //run('cd {{deploy_path}} && mv composer.phar {{deploy_path}}/composer.phar');
        }
    });

    task('deploy:vendors', function () {
        run('cd {{release_path}} && {{bin/composer}} install {{composer_options}}');
    });

    task('deploy:symlink', function () {
        run("cd {{deploy_path}} && ln -sfn {{release_path}} current");
        run("cd {{deploy_path}} && ln -sfn current payment-service");
    });

    task('deploy', [
        'deploy:info',
        'deploy:prepare',
        'deploy:vendors',
        'deploy:publish'
    ]);

    before('deploy:vendors', 'check:composer');
    after('deploy:failed', 'deploy:unlock');
} catch (Exception $e) {
    throw new Exception('Unable to load application'. $e->getMessage());
}