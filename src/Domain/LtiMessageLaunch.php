<?php

namespace GrotonSchool\Slim\LTI\Domain;

use Exception;
use Packback\Lti1p3;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\IDatabase;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\LtiException;
use Packback\Lti1p3\LtiOidcLogin;

class LtiMessageLaunch extends Lti1p3\LtiMessageLaunch
{
    public const PARAM_VALIDATE_STATE_NONCE = 'validate_state_nonce';

    protected array $request;

    public function __construct(
        protected IDatabase $db,
        protected ICache $cache,
        protected ICookie $cookie,
        protected ILtiServiceConnector $serviceConnector
    ) {
        parent::__construct(
            $this->db,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        );
    }

    public function setRequest(array $request): self
    {
        $this->request = $request;
        parent::setRequest($this->request);
        return $this;
    }

    protected function validateState(): self
    {
        if (isset($this->request[self::PARAM_VALIDATE_STATE_NONCE])) {
            if (!$this->cache->checkNonceIsValid($this->request[self::PARAM_VALIDATE_STATE_NONCE], $this->request['state'])) {
                throw new LtiException(static::ERR_STATE_NOT_FOUND);
            }
        } else {
            // Check State for OIDC.
            if ($this->cookie->getCookie(LtiOidcLogin::COOKIE_PREFIX . $this->request['state']) !== $this->request['state']) {
                // Error if state doesn't match
                throw new LtiException(static::ERR_STATE_NOT_FOUND);
            }
        }
        return $this;
    }

    public function getLaunchData(): array
    {
        $this->validateJwtFormat();
        return parent::getLaunchData();
    }
}
