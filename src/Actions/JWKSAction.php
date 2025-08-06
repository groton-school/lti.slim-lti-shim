<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\JwksEndpoint;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class JWKSAction
{
    public function __construct(private DatabaseInterface $database)
    {
    }

    protected function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        $registration = $this->database->findRegistrationByIssuer(
            $request->getParsedBody()['iss']
        );
        if ($registration) {
            return $response->withJson(JwksEndpoint::new([
                $registration->getKid() => $registration->getToolPrivateKey(),
            ])->getPublicJwks());
        }
        return $response;
    }
}
