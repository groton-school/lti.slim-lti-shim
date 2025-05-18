<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use Psr\Http\Message\ResponseInterface;

interface RegistrationConfigureActionInterface
{
    /**
     * Interactive configuration of the LTI Tool registration based on the
     * capabilities provided in the LTI Consumer configuration $config
     *
     * TODO better typing/objectification of $config
     *
     * if RegistrationCompleteAction is used as an endpoint action, it expects
     * to receive the final version of the LTI Tool Registration as a POST
     * form parameter with the name stored in
     * `RegistrationCompleteAction::REGISTRATION_PARAM` and `$registrationId`
     * in a POST form body parameter with the name stored in
     * `RegistrationCompleteAction:REGISTRATION_ID_PARAM`
     * 
     * @param ResponseInterface $response
     * @param array $config
     * @param string $requestId
     * @return ResponseInterface
     */
    public function configure(
        ResponseInterface $response,
        array $config,
        string $registrationId
    ): ResponseInterface;
}
