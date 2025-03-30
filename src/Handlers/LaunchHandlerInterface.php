<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Handlers;

use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface;

interface LaunchHandlerInterface
{
    public function handle(
        ResponseInterface $response,
        LtiMessageLaunch $launch
    ): ResponseInterface;
}
