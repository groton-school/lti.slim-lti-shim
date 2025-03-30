<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\GAE\SettingsInterface;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class LaunchAction extends AbstractAction
{
    protected LaunchHandlerInterface $launchHandler;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        CacheInterface $cache,
        CookieInterface $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings,
        LaunchHandlerInterface $launchHandler,
    ) {
        parent::__construct(
            $logger,
            $database,
            $cache,
            $cookie,
            $serviceConnector,
            $settings
        );
        $this->launchHandler = $launchHandler;
    }

    protected function action(): ResponseInterface
    {
        $launch = LtiMessageLaunch::new(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        )->initialize($this->request->getParsedBody());
        return $this->launchHandler->handle($this->response, $launch);
    }
}
