<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Slim\Http\Response;

class RegisterAction extends AbstractConfigurationAction
{
    protected function action(): Response
    {
        $openid_configuration = $this->request->getQueryParam('openid_configuration');
        $registration_token = $this->request->getQueryParam('registration_token');
        $client = new Client();
        $response = $client->get($openid_configuration, [
          RequestOptions::HEADERS => [
            'Authorization' => "Bearer $registration_token"
          ]
        ]);
        $config = json_decode($response->getBody()->getContents(), true);

        // FIXME where does the deployment_id come from, and how can I register it?

        $this->cookie->setCookie(
            self::CONSUMER_CONFIGURATION_COOKIE,
            $this->configCache->cacheConsumerConfiguration($config)
        );
        $this->cookie->setCookie(
            self::REGISTRATION_TOKEN_COOKIE,
            $this->configCache->cacheRegistrationToken($registration_token)
        );
        return $this->configure->interactiveConfiguration($config);
    }
}
