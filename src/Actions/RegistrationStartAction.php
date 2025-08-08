<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\Norms\AbstractAction;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class RegistrationStartAction extends AbstractAction
{
    public function __construct(
        private CacheInterface $cache,
        private RegistrationConfigureActionInterface $configureAction
    ) {
    }

    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $openid_configuration = $request->getQueryParam('openid_configuration');
        $registration_token = $request->getQueryParam('registration_token');
        $client = new Client();
        $response = $client->get($openid_configuration, [
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer $registration_token"
            ]
        ]);
        $config = json_decode($response->getBody()->getContents(), true);

        // FIXME where does the deployment_id come from, and how can I register it?

        $registrationId = $this->cache->cacheRegistrationConfiguration($config, $registration_token);
        return $this->configureAction->configure($response, $config, $registrationId);
    }
}
