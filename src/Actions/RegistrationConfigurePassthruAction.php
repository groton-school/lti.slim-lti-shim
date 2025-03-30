<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\SettingsInterface;
use JsonSerializable;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class RegistrationConfigurePassthruAction extends AbstractAction implements RegistrationConfigureActionInterface
{
    private RegistrationCompleteActionInterface $completeAction;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        CacheInterface $cache,
        CookieInterface $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings,
        RegistrationCompleteActionInterface $completeAction
    ) {
        parent::__construct(
            $logger,
            $database,
            $cache,
            $cookie,
            $serviceConnector,
            $settings
        );
        $this->completeAction = $completeAction;
    }

    public function configure(
        ResponseInterface $response,
        JsonSerializable $config
    ): ResponseInterface {
        return $this->completeAction->complete(
            $response,
            $this->settings->getToolRegistration()
        );
    }

    public function action(): ResponseInterface
    {
        return $this->response->withStatus(403, 'Forbidden');
    }
}
