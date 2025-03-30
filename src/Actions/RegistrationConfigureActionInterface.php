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
     * TODO better typing/objectification of $config
     *
     * @param ResponseInterface $response
     * @param JsonSerializable $config
     * @return ResponseInterface
     */
    public function configure(
        ResponseInterface $response,
        JsonSerializable $config
    ): ResponseInterface;
}
