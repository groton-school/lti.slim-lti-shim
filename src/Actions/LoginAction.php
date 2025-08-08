<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\LtiOidcLogin;
use Packback\Lti1p3\OidcException;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class LoginAction extends AbstractViewsAction
{
    public function __construct(
        protected DatabaseInterface $database,
        protected CacheInterface $cache,
        protected CookieInterface $cookie
    ) {
        parent::__construct();
    }

    protected function invokeHook(
        ServerRequest $request,
        Response $response
    ): ResponseInterface {
        $login = LtiOidcLogin::new($this->database, $this->cache, $this->cookie);
        try {
            // TODO should I be verifying that target_link_uri is my launch uri?
            $redirect = $login->getRedirectUrl($request->getParam('target_link_uri'), $request->getParsedBody());
            return $this->views->render(
                $response,
                'launch/login.php',
                [
                    'redirect' => $redirect,
                    'lti_storage_target' => $request->getParam('lti_storage_target')
                ]
            );
        } catch (OidcException $e) {
            return $response->withStatus(401, $e->getMessage());
        }
    }
}
