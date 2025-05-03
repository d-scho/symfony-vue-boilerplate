<?php

declare(strict_types=1);

use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $doctrineMigrations): void {
    $doctrineMigrations
        ->em('default')
        ->migrationsPath(
            'DoctrineMigrations',
            '%kernel.project_dir%/doctrine_migrations'
        )
    ;
};