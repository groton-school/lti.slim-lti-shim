<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI;

use DI;
use DI\ContainerBuilder;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces as Packback;
use Packback\Lti1p3\LtiServiceConnector;

class Dependencies
{
    public static function inject(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addDefinitions([
            // autowire packbackbooks/lti-1p3-tool implementations
            Packback\ILtiServiceConnector::class => DI\autowire(LtiServiceConnector::class),
            Packback\ICookie::class => DI\get(CookieInterface::class),
            Packback\ICache::class => DI\get(CacheInterface::class),
            Packback\IDatabase::class => DI\get(DatabaseInterface::class),

        ]);
    }
}
