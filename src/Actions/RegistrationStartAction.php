<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\SettingsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Log\LoggerInterface;
use Slim\Http\Response;

class RegistrationStartAction extends AbstractAction
{
    protected RegistrationConfigureActionInterface $configureAction;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        CacheInterface $cache,
        CookieInterface $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings,
        RegistrationConfigureActionInterface $configureAction,
    ) {
        parent::__construct(
            $logger,
            $database,
            $cache,
            $cookie,
            $serviceConnector,
            $settings
        );
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

        $id = $this->cache->cacheRegistrationConfiguration($config, $registration_token);
        $this->cookie->setCookie(self::REGISTRATION_COOKIE, $id, 60 * 60 * 60, ['SameSite' => 'Strict']);
        return $this->configureAction->configure($this->response, $config);
    }
}
