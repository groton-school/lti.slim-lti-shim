<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\Actions\AbstractAction;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\Traits\ViewsTrait;
use Packback\Lti1p3\LtiOidcLogin;
use Packback\Lti1p3\OidcException;
use Psr\Http\Message\ResponseInterface;

class LoginAction extends AbstractAction
{
    use ViewsTrait;

    public function __construct(
        protected DatabaseInterface $database,
        protected CacheInterface $cache,
        protected CookieInterface $cookie
    ) {
        $this->initSlmLtiShimViews();
    }

    protected function action(): ResponseInterface
    {
        $login = LtiOidcLogin::new($this->database, $this->cache, $this->cookie);
        try {
            // TODO should I be verifying that target_link_uri is my launch uri?
            $redirect = $login->getRedirectUrl($this->request->getParam('target_link_uri'), $this->request->getParsedBody());
            return $this->slimLtiShimViews->render(
                $this->response,
                'redirect.php',
                [
                    'redirect' => $redirect,
                    'lti_storage_target' => $this->request->getParam('lti_storage_target')
                ]
            );
        } catch (OidcException $e) {
            return $this->response->withStatus(401, $e->getMessage());
        }
    }
}
