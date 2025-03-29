<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Domain;

use JsonSerializable;
use Packback\Lti1p3\LtiRegistration;

/**
 * @property-read string $issuer
 * @property-read string $jwks_uri
 * @property-read string $token_endpoint
 * @property-read string $authorization_endpoint
 * @property-read string $client_id,
 * @property-read string $tool_private_key
 */
class Registration extends LtiRegistration implements JsonSerializable
{
    private array $data;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->data = $data;
        if (isset($data['issuer']) && !$this->getIssuer()) {
            $this->setIssuer($data['issuer']);
        }
        if (isset($data['client_id']) && !$this->getClientId()) {
            $this->setClientId($data['client_id']);
        }
        if (isset($data['jwks_uri']) && !$this->getKeySetUrl()) {
            $this->setKeySetUrl($data['jwks_uri']);
        }
        if (isset($data['token_endpoint']) && !$this->getAuthTokenUrl()) {
            $this->setAuthTokenUrl($data['token_endpoint']);
        }
        if (isset($data['authorization_endpoint']) && !$this->getAuthLoginUrl()) {
            $this->setAuthLoginUrl($data['authorization_endpoint']);
        }
        if (!$this->getToolPrivateKey()) {
            $key = openssl_pkey_new();
            openssl_pkey_export($key, $pem);
            $this->setToolPrivateKey($pem);
        }
    }

    /**
     * @param string[] $deployments
     */
    public function setDeployments(array $deployments): self
    {
        $this->data['deployments'] = $deployments;
        return $this;
    }

    public function hasDeployment(string $deploymentId): bool
    {
        return in_array($deploymentId, $this->data['deployments']);
    }


    /**
     * @param string $property Property name
     *
     * @return string | null
     */
    public function __get(string $property)
    {
        if (isset($this->data[$property])) {
            return $this->data[$property];
        }
        return null;
    }

    public function toArray(): array
    {
        return array_merge($this->data, [
            'issuer' => $this->getIssuer(),
            'jwks_uri' => $this->getKeySetUrl(),
            'token_endpoint' => $this->getAuthTokenUrl(),
            'authorization_endpoint' => $this->getAuthLoginUrl(),
            'client_id' => $this->getClientId(),
            'tool_private_key' => $this->getToolPrivateKey()
        ]);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
