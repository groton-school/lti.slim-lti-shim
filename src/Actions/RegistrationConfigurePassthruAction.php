<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\Actions\LoggerTrait;
use GrotonSchool\Slim\LTI\SettingsInterface;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class RegistrationConfigurePassthruAction extends AbstractAction implements RegistrationConfigureActionInterface
{
    use LoggerTrait;


    public function __construct(
        LoggerInterface $logger,
        private SettingsInterface $settings,
        private RegistrationCompleteAction $completeAction
    ) {
        $this->initLogger($logger);
        $this->completeAction = $completeAction;
    }

    public function configure(
        ResponseInterface $response,
        JsonSerializable | array $config,
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
        $this->logger->debug('RegistrationConfigurePassthruAction does not handle endpoints. Invoke its configure() method instead.');
        return $this->response->withStatus(501, 'Server misconfigured');
    }
}
