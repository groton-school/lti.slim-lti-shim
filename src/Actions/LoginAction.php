<?php

declare(strict_types=1);

namespace GrotonSchool\SlimLTI\Actions;

use Packback\Lti1p3\LtiOidcLogin;
use Psr\Http\Message\ResponseInterface as Response;

class LoginAction extends LTIAction
{
    protected function action(): Response
    {
        return $this->response
            ->withStatus(302)
            ->withHeader(
                'Location',
                LtiOidcLogin::new(
                    $this->database,
                    $this->cache,
                    $this->cookie
                )->getRedirectUrl(
                    $this->projectUrl . '/lti/launch',
                    $this->request->getParsedBody()
                )
            );
    }
}
