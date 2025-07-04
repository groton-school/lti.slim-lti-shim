<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Slim\Http\Response;

class RegistrationStartAction extends AbstractAction
{
    protected RegistrationConfigureActionInterface $configureAction;

    public function __construct(RegistrationConfigureActionInterface $configureAction)
    {
        $this->configureAction = $configureAction;
    }

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

        $registrationId = $this->cache->cacheRegistrationConfiguration($config, $registration_token);
        return $this->configureAction->configure($this->response, $config, $registrationId);
    }
}
