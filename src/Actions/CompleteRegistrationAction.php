<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Domain\Registration;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class CompleteRegistrationAction extends AbstractConfigurationAction
{
    public function action(): ResponseInterface
    {
        $registration = $this->request->getParsedBodyParam('registration');
        $registration_token = $this->configCache->getRegistrationToken(
            $this->cookie->getCookie(self::REGISTRATION_TOKEN_COOKIE)
        );
        $config = $this->configCache->getConsumerConfiguration(
            $this->cookie->getCookie(self::CONSUMER_CONFIGURATION_COOKIE)
        );
        $client = new Client();
        $response = $client->post($config['registration_endpoint'], [
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer $registration_token"
            ],
            RequestOptions::JSON => $registration
        ]);
        $registration = json_decode($response->getBody()->getContents(), true);
        $this->database->saveRegistration(new Registration(array_merge($registration, $config)));
        return $this->renderer->render($this->response, 'close.php');
    }
}
