<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI;

use DI;
use DI\ContainerBuilder;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\Middleware\CookieMiddleware;
use GrotonSchool\Slim\Norms\DependenciesInterface;
use Packback\Lti1p3\Interfaces as Packback;
use Packback\Lti1p3\LtiServiceConnector;
use Psr\Container\ContainerInterface;

class Dependencies implements DependenciesInterface
{
    public static function inject(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addDefinitions([
            // autowire packbackbooks/lti-1p3-tool implementations
            Packback\ILtiServiceConnector::class => DI\autowire(LtiServiceConnector::class),
            Packback\ICookie::class => DI\get(CookieMiddleware::class),
            Packback\ICache::class => DI\get(CacheInterface::class),
            Packback\IDatabase::class => DI\get(DatabaseInterface::class),
            CookieInterface::class => DI\get(CookieMiddleware::class),
            CookieMiddleware::class => DI\autowire()
        ]);
    }
}
