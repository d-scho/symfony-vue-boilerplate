<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Config\Framework\SessionConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $frameworkConfig) {
    $sessionDurationInSeconds = 1209600; // 14 days

    /** @var SessionConfig $session */
    $session = $frameworkConfig->session();

    $session
        ->enabled(true)
        ->handlerId('session.handler.native_file')
        ->savePath('%kernel.project_dir%/var/sessions/%kernel.environment%')
        ->cookiePath('/')
        ->cookieSecure(true)
        ->cookieHttponly(true)
        ->cookieSamesite(Cookie::SAMESITE_STRICT)
        ->cookieLifetime($sessionDurationInSeconds) // lifetime of session cookie frontend
        ->gcMaxlifetime($sessionDurationInSeconds) // lifetime of sessions backend
        ->gcProbability(1) // probability of clean up (only for clearing memory, session itself should be expired nonetheless)
        ->gcDivisor(1) // divisor of said probability
    ;
};
