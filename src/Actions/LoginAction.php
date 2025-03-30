<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use Packback\Lti1p3\LtiOidcLogin;
use Packback\Lti1p3\OidcException;
use Psr\Http\Message\ResponseInterface;

class LoginAction extends AbstractAction
{
    protected function action(): ResponseInterface
    {
        $login = LtiOidcLogin::new($this->database, $this->cache, $this->cookie);
        try {
            // TODO should I be verifying that target_link_uri is my launch uri?
            $redirect = $login->getRedirectUrl($this->request->getParam('target_link_uri'), $this->request->getParsedBody());
            return $this->renderer->render(
                $this->response,
                'redirect.php',
                ['redirect' => $redirect]
            );
        } catch (OidcException $e) {
            return $this->response->withStatus(401, $e->getMessage());
        }
    }
}
