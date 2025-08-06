<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Domain\LtiMessageLaunch;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\LtiOidcLogin;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class LaunchAction extends AbstractViewsAction
{
    public function __construct(
        private DatabaseInterface $database,
        private CacheInterface $cache,
        private CookieInterface $cookie,
        private ILtiServiceConnector $serviceConnector,
        private LaunchHandlerInterface $launchHandler
    ) {
        parent::__construct();
    }

    public function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        $launch = new LtiMessageLaunch(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        );
        if (
            !$this->cookie->getCookie(LtiOidcLogin::COOKIE_PREFIX . $request->getParam('state')) &&
            !$request->getParam(LtiMessageLaunch::PARAM_VALIDATE_STATE_NONCE)
        ) {
            $launch->setRequest($request->getParams());
            $state = $request->getParam('state');
            $nonce = LtiOidcLogin::secureRandomString('validate_state_');
            $jwt = $launch->getLaunchData();

            $this->cache->cacheNonce($nonce, $state);
            return $this->slimLtiShimViews->render(
                $response,
                'launch/validateState.php',
                [
                    'action' => $request->getUri()->getPath(),
                    'state' => $state,
                    'nonce' => $nonce,
                    'nonce_param' => LtiMessageLaunch::PARAM_VALIDATE_STATE_NONCE,
                    'lti_storage_target' => $request->getParam('lti_storage_target'),
                    'authLoginUrl' => $this->database->findRegistrationByIssuer($jwt['iss'], $jwt['aud'])->getAuthLoginUrl(),
                    'post' => $request->getParams()
                ]
            );
        } else {
            $launch->initialize($request->getParams());
            return $this->launchHandler->handle($response, $launch);
        }
    }
}
