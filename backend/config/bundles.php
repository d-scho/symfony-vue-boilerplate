<?php

declare(strict_types=1);

use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Nelmio\CorsBundle\NelmioCorsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;

return [
    FrameworkBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    NelmioCorsBundle::class => ['all' => true],
    LexikJWTAuthenticationBundle::class => ['all' => true],
];
