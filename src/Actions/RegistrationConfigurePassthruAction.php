<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\SettingsInterface;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class RegistrationConfigurePassthruAction extends AbstractAction implements RegistrationConfigureActionInterface
{
    private RegistrationCompleteAction $completeAction;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        CacheInterface $cache,
        CookieInterface $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings,
        RegistrationCompleteAction $completeAction
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
        array $config,
        string $registrationId
    ): ResponseInterface {
        return $this->completeAction->complete(
            $response,
            $this->settings->getToolRegistration(),
            $registrationId
        );
    }

    public function action(): ResponseInterface
    {
        $this->logger->debug('RegistrationConfigurePassthruAction does not handle endpoints. Invoke its configre() method instead.');
        return $this->response->withStatus(403, 'Forbidden');
    }
}
