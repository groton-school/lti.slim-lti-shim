<?php

declare(strict_types=1);

namespace GrotonSchool\SlimLTI\Actions;

use Packback\Lti1p3\JwksEndpoint;
use Psr\Http\Message\ResponseInterface as Response;

class JWKSAction extends LTIAction
{
    protected function action(): Response
    {
        $registration = $this->database->findRegistrationByIssuer(
            $this->request->getParsedBody()['id']
        );
        if ($registration) {
            $this->response->getBody()->write(
                json_encode(
                    JwksEndpoint::new([
                        $registration->getKid() => $registration->getToolPrivateKey(),
                    ])->getPublicJwks()
                )
            );
            return $this->response->withAddedHeader(
                'Content-Type',
                'application/json'
            );
        }
    }
}
