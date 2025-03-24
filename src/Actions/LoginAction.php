<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use Packback\Lti1p3\LtiOidcLogin;
use Packback\Lti1p3\OidcException;
use Psr\Http\Message\ResponseInterface as Response;

class LoginAction extends LTIAction
{
    protected function action(): Response
    {
        $login = LtiOidcLogin::new($this->database, $this->cache, $this->cookie);
        try {
            $redirect = $login->getRedirectUrl($this->projectUrl . '/lti/launch', $this->request->getParsedBody());
        } catch (OidcException $e) {
            var_dump($e);
        }
        return $this->response->withHeader('Location', $redirect);
    }
}
