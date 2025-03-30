<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\SettingsInterface;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Log\LoggerInterface;

abstract class AbstractConfigurationAction extends AbstractAction
{
    protected const REGISTRATION_TOKEN_COOKIE = 'rtid';
    protected const CONSUMER_CONFIGURATION_COOKIE = 'cfid';

    protected ConfigurationActionInterface $configure;
    protected CacheInterface $configCache;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        ICache $cache,
        ICookie $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings,
        ConfigurationActionInterface $configure,
        CacheInterface $configCache
    ) {
        parent::__construct($logger, $database, $cache, $cookie, $serviceConnector, $settings);
        $this->configure = $configure;
        $this->configCache = $configCache;
    }
}
