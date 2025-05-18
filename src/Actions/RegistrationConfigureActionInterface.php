<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

interface RegistrationConfigureActionInterface
{
    /**
     * Interactive configuration of the LTI Tool registration based on the
     * capabilities provided in the LTI Consumer configuration $config
     *
     * if RegistrationCompleteAction is used as an endpoint action, it expects
     * to receive the final version of the LTI Tool Registration as a POST
     * form parameter with the name stored in
     * `RegistrationCompleteAction::REGISTRATION_PARAM` and `$registrationId`
     * in a POST form body parameter with the name stored in
     * `RegistrationCompleteAction:REGISTRATION_ID_PARAM`
     *
     * @param ResponseInterface $response
     * @param JsonSerialaizable | array $config
     * @param string $requestId
     * @return ResponseInterface
     */
    public function configure(
        ResponseInterface $response,
        JsonSerializable | array $config,
        string $registrationId
    ): ResponseInterface;
}
