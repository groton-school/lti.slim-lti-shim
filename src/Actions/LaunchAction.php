<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use App\Application\Actions\AbstractAction;
use GrotonSchool\Slim\LTI\Domain\LtiMessageLaunch;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\LTI\Traits\ViewsTrait;
use Packback\Lti1p3\LtiOidcLogin;
use Psr\Http\Message\ResponseInterface;

class LaunchAction extends AbstractAction
{
    use ViewsTrait;

    protected LaunchHandlerInterface $launchHandler;

    public function __construct(LaunchHandlerInterface $launchHandler)
    {
        $this->initSlmLtiShimViews();
        $this->launchHandler = $launchHandler;
    }

    protected function action(): ResponseInterface
    {
        $launch = new LtiMessageLaunch(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        );
        if (
            !$this->cookie->getCookie(LtiOidcLogin::COOKIE_PREFIX . $this->request->getParam('state')) &&
            !$this->request->getParam(LtiMessageLaunch::PARAM_VALIDATE_STATE_NONCE)
        ) {
            $launch->setRequest($this->request->getParams());
            $state = $this->request->getParam('state');
            $nonce = LtiOidcLogin::secureRandomString('validate_state_');
            $jwt = $launch->getLaunchData();

            $this->cache->cacheNonce($nonce, $state);
            return $this->slimLtiShimViews->render($this->response, 'validateState.php', [
                'action' => $this->request->getUri()->getPath(),
                'state' => $state,
                'nonce' => $nonce,
                'nonce_param' => LtiMessageLaunch::PARAM_VALIDATE_STATE_NONCE,
                'lti_storage_target' => $this->request->getParam('lti_storage_target'),
                'authLoginUrl' => $this->database->findRegistrationByIssuer($jwt['iss'], $jwt['aud'])->getAuthLoginUrl(),
                'post' => $this->request->getParams()
            ]);
        } else {
            $launch->initialize($this->request->getParams());
            return $this->launchHandler->handle($this->response, $launch);
        }
    }
}
