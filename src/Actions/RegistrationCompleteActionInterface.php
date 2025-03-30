<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

interface RegistrationCompleteActionInterface
{
    /**
     * Complete LTI Tool registration with the LIT Consumer
     *
     * TODO better typing/objectification of $registration
     *
     * @param ResponseInterface $response
     * @param JsonSerializable $registration
     * @return ResponseInterface
     */
    public function complete(
        ResponseInterface $response,
        JsonSerializable $registration
    ): ResponseInterface;
}
