<?php

namespace Deployer;

require 'recipe/laravel.php';

set('application', 'uml-backend');

set('repository', 'git@github.com:aleksej192/uml.git');

set('git_tty', false);
add('shared_files', [
    'backend/.env'
]);

add('shared_dirs', [
    'vendor',
    'storage',
]);

add('writable_dirs', [
    'storage'
]);
set('allow_anonymous_stats', false);

set('laravel_version', function () {
    $result = run('cd {{release_path}} && {{bin/php}} artisan --version');

    preg_match_all('/(\d+\.?)+/', $result, $matches);

    $version = $matches[0][0] ?? 5.5;

    return $version;
});

desc('Installing vendors');
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
    }
    run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
});

desc('Execute artisan migrate');
task('artisan:migrate', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate --force');
})->once();

desc('Execute artisan migrate:fresh');
task('artisan:migrate:fresh', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate:fresh --force');
});

desc('Execute artisan migrate:rollback');
task('artisan:migrate:rollback', function () {
    $output = run('{{bin/php}} {{release_path}}/artisan migrate:rollback --force');
    writeln('<info>' . $output . '</info>');
});

desc('Execute artisan migrate:status');
task('artisan:migrate:status', function () {
    $output = run('{{bin/php}} {{release_path}}/artisan migrate:status');
    writeln('<info>' . $output . '</info>');
});

desc('Execute artisan db:seed');
task('artisan:db:seed', function () {
    $output = run('{{bin/php}} {{release_path}}/artisan db:seed --force');
    writeln('<info>' . $output . '</info>');
});

desc('Execute artisan cache:clear');
task('artisan:cache:clear', function () {
    run('{{bin/php}} {{release_path}}/artisan cache:clear');
});

desc('Execute artisan config:cache');
task('artisan:config:cache', function () {
    run('{{bin/php}} {{release_path}}/artisan config:cache');
});

desc('Execute artisan route:cache');
task('artisan:route:cache', function () {
    run('{{bin/php}} {{release_path}}/artisan route:cache');
});

desc('Execute artisan view:clear');
task('artisan:view:clear', function () {
    run('{{bin/php}} {{release_path}}/artisan view:clear');
});

desc('Execute artisan view:cache');
task('artisan:view:cache', function () {
    $needsVersion = 5.6;
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/artisan view:cache');
    }
});

desc('Execute artisan event:cache');
task('artisan:event:cache', function () {
    $needsVersion = '5.8.9';
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/artisan event:cache');
    }
});

desc('Execute artisan event:clear');
task('artisan:event:clear', function () {
    $needsVersion = '5.8.9';
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/artisan event:clear');
    }
});

desc('Execute artisan optimize');
task('artisan:optimize', function () {
    $deprecatedVersion = 5.5;
    $readdedInVersion = 5.7;
    $currentVersion = get('laravel_version');

    if (
        version_compare($currentVersion, $deprecatedVersion, '<') ||
        version_compare($currentVersion, $readdedInVersion, '>=')
    ) {
        run('{{bin/php}} {{release_path}}/artisan optimize');
    }
});

desc('Execute artisan optimize:clear');
task('artisan:optimize:clear', function () {
    $needsVersion = 5.7;
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/artisan optimize:clear');
    }
});

desc('Execute artisan queue:restart');
task('artisan:queue:restart', function () {
    run('{{bin/php}} {{release_path}}/artisan queue:restart');
});

desc('Execute artisan horizon:terminate');
task('artisan:horizon:terminate', function () {
    run('{{bin/php}} {{release_path}}/artisan horizon:terminate');
});

desc('Execute artisan storage:link');
task('artisan:storage:link', function () {
    $needsVersion = 5.3;
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/artisan storage:link');
    }
});

desc('Make symlink for public disk');
task('deploy:public_disk', function () {
    // Remove from source.
    run('if [ -d $(echo {{release_path}}/public/storage) ]; then rm -rf {{release_path}}/public/storage; fi');

    // Create shared dir if it does not exist.
    run('mkdir -p {{deploy_path}}/shared/storage/app/public');

    // Symlink shared dir to release dir
    run('{{bin/symlink}} {{deploy_path}}/shared/storage/app/public {{release_path}}/public/storage');
});

localhost()
    ->stage('prod')
    ->user('root')
    ->set('branch', 'master')
    ->set('composer', '/usr/local/bin/composer')
    ->set('composer_options', 'install --verbose --no-progress --no-interaction --optimize-autoloader')
    ->set('deploy_path', '/var/www/uml/backend');

host('213.226.124.77')
    ->stage('dev')
    ->user('root')
    ->set('branch', 'master')
    ->set('composer', '/usr/local/bin/composer')
    ->set('composer_options', 'install --verbose --no-progress --no-interaction --optimize-autoloader')
    ->set('deploy_path', '/var/www/uml/backend');

desc('Перезагрузка PHP-FPM');
task('fpm:restart', 'sudo /etc/init.d/php8.1-fpm restart');

after('deploy:failed', 'deploy:unlock');

after('artisan:migrate', 'artisan:db:seed');

before('deploy:symlink', 'artisan:migrate');

task('deploy:after', [
    'fpm:restart',
]);

after('deploy', 'deploy:after');
