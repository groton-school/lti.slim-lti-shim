<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use GrotonSchool\Slim\LTI\SettingsInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

/**
 * @see https://github.com/slimphp/Slim-Skeleton/blob/main/src/Application/Actions/User/UserAction.php
 */
abstract class AbstractAction extends \GrotonSchool\Slim\Actions\AbstractAction
{
    protected const REGISTRATION_COOKIE = 'registration';

    protected DatabaseInterface $database;
    protected CacheInterface $cache;
    protected CookieInterface $cookie;
    protected ILtiServiceConnector $serviceConnector;
    protected SettingsInterface $settings;
    protected PhpRenderer $renderer;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        CacheInterface $cache,
        CookieInterface $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings
    ) {
        parent::__construct($logger);
        $this->database = $database;
        $this->cache = $cache;
        $this->cookie = $cookie;
        $this->serviceConnector = $serviceConnector;
        $this->settings = $settings;
        $this->renderer = new PhpRenderer(__DIR__ . '/../../templates');
    }
}
