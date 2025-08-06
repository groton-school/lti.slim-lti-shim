<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\Actions\LoggerTrait;
use GrotonSchool\Slim\LTI\SettingsInterface;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class RegistrationConfigurePassthruAction implements RegistrationConfigureActionInterface
{
    use LoggerTrait;


    public function __construct(
        private LoggerInterface $logger,
        private SettingsInterface $settings,
        private RegistrationCompleteAction $completeAction
    ) {
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

    public function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        $this->logger->debug('RegistrationConfigurePassthruAction does not handle endpoints. Invoke its configure() method instead.');
        return $response->withStatus(501, 'Server misconfigured');
    }
}
