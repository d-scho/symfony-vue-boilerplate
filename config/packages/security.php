<?php

declare(strict_types=1);

use DScho\Backend\JsonNoContentSuccessHandler;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    $security
        ->passwordHasher(PasswordAuthenticatedUserInterface::class, [
            'algorithm' => 'plaintext',
        ]);

    // provider definition
    $memoryProvider = $security
        ->provider('app_user_provider')
        ->memory();
    $memoryProvider
        ->user('admin')
        ->password('admin-pw')
        ->roles([
            'ROLE_ADMIN',
            'ROLE_USER',
        ]);
    $memoryProvider
        ->user('user')
        ->password('user-pw')
        ->roles([
            'ROLE_USER',
        ]);


    // firewall definitions
    // firewalls get activated based on the pattern - AUTHENTICATION
    // here, 'dev' will be triggered on the defined pattern and will disable all security
    $security
        ->firewall('dev')
        ->pattern('^/(_(profiler|wdt)|css|images|js)/')
        ->security(false);
    // then, 'main' is triggered for all other routes
    $security
        ->firewall('main')
        ->pattern('^.*$')
        ->provider('app_user_provider')
        ->lazy(true)
        ->jsonLogin()
            // this basically registers a request listener for POST /login
            // that is even evaluated before access control if not authenticated
            ->checkPath('/login')
            ->usernamePath('username')
            ->passwordPath('password')
            ->successHandler(JsonNoContentSuccessHandler::class);

    // access control is just a broader way of covering access rules - AUTHORIZATION
    // i.e., it does not directly relate the firewall
    // e.g., if ->security(false) was not set on the 'dev' firewall, this would also cover it
    $security->accessControl()
        ->path('^.*$')
        ->roles(['ROLE_USER']);
};
