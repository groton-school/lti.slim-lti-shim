<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use Exception;
use GrotonSchool\Slim\LTI\Domain\Registration;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class RegistrationCompleteAction extends AbstractAction
{
    public function complete(ResponseInterface $response, array $registration, $registrationId): ResponseInterface
    {
    public const REGISTRATION_PARAM = 'registration';
    public const REGISTRATION_ID_PARAM = 'registration_id';

        $data = $this->cache->getRegistrationConfiguration($registrationId);
        $config = $data[CacheInterface::OPENID_CONFIGURATION];
        $registration_token = $data[CacheInterface::REGISTRATION_TOKEN];
        $client = new Client();
        $regResponse = $client->post($config['registration_endpoint'], [
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer $registration_token"
            ],
            RequestOptions::JSON => $registration
        ]);
        $registration = json_decode($regResponse->getBody()->getContents(), true);
        $this->database->saveRegistration(new Registration(array_merge($registration, $config)));
        return $this->renderer->render($response, 'completeRegistration.php');

    }

    public function action(): ResponseInterface
    {
        return $this->complete(
            $this->response,
            $this->request->getParsedBodyParam(self::REGISTRATION_PARAM),
            $this->request->getParsedBodyParam(self::REGISTRATION_ID_PARAM)
        );
    }
}
