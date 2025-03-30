<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Domain\Registration;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class RegistrationCompleteAction extends AbstractAction implements RegistrationCompleteActionInterface
{
    public function complete(ResponseInterface $response, array $registration): ResponseInterface
    {
        $id = $this->cookie->getCookie(self::REGISTRATION_COOKIE);
        $this->cookie->deleteCookie(self::REGISTRATION_COOKIE);
        $data = $this->cache->getRegistrationConfiguration($id);
        $config = $data[CacheInterface::OPENID_CONFIGURATION];
        $registration_token = $data[CacheInterface::REGISTRATION_TOKEN];
        $client = new Client();
        $response = $client->post($config['registration_endpoint'], [
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer $registration_token"
            ],
            RequestOptions::JSON => $registration
        ]);
        $registration = json_decode($response->getBody()->getContents(), true);
        $this->database->saveRegistration(new Registration(array_merge($registration, $config)));
        return $this->renderer->render($response, 'completeRegistration.php');

    }

    public function action(): ResponseInterface
    {
        $registration = $this->request->getParsedBodyParam('registration');
        return $this->complete($this->response, $registration);
    }
}
