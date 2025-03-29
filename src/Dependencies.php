<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI;

use DI;
use GrotonSchool\Slim\LTI\Infrastructure\Cookie;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\LtiServiceConnector;

class Dependencies
{
    public static function addDefinitions(DI\ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addDefinitions([
            ILtiServiceConnector::class => DI\autowire(LtiServiceConnector::class),
            ICookie::class => DI\autowire(Cookie::class)
        ]);
    }
}
