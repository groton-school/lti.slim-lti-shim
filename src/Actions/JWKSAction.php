<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use Packback\Lti1p3\JwksEndpoint;
use Psr\Http\Message\ResponseInterface;

class JWKSAction extends AbstractAction
{
    protected function action(): ResponseInterface
    {
        $registration = $this->database->findRegistrationByIssuer(
            $this->request->getParsedBody()['iss']
        );
        if ($registration) {
            return $this->response->withJson(JwksEndpoint::new([
                $registration->getKid() => $registration->getToolPrivateKey(),
            ])->getPublicJwks());
        }
        return $this->response;
    }
}
