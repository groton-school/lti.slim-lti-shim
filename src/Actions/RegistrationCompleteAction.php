<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\Actions\AbstractAction;
use GrotonSchool\Slim\LTI\Domain\Registration;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\LTI\Traits\ViewsTrait;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

class RegistrationCompleteAction extends AbstractAction
{
    use ViewsTrait;

    public const REGISTRATION_PARAM = 'registration';
    public const REGISTRATION_ID_PARAM = 'registration_id';

    public function __construct(
        private DatabaseInterface $database,
        private CacheInterface $cache
    ) {
        $this->initSlmLtiShimViews();
    }

    public function complete(
        ResponseInterface $response,
        JsonSerializable | array $registration,
        string $registrationId
    ): ResponseInterface {
        $data = $this->cache->getRegistrationConfiguration($registrationId);
        $config = $data[CacheInterface::OPENID_CONFIGURATION];
        if (!is_array($config)) {
            $config = json_decode(json_encode($config), true);
        }
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
        return $this->slimLtiShimViews->render($response, 'registration/complete.php');
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
