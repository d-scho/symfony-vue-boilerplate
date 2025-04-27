<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Config\Framework\SessionConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $frameworkConfig) {
    $frameworkConfig->test(true);
};
