<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\SettingsInterface;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Log\LoggerInterface;

abstract class AbstractRegistrationAction extends AbstractAction
{
    protected const REGISTRATION_COOKIE = 'registration';

    protected RegistrationConfigureActionInterface $configureAction;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        CacheInterface $cache,
        CookieInterface $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings,
        RegistrationConfigureActionInterface $configureAction,
    ) {
        parent::__construct(
            $logger,
            $database,
            $cache,
            $cookie,
            $serviceConnector,
            $settings
        );
        $this->configureAction = $configureAction;
    }
}
