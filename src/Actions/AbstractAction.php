<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Application\SettingsInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Log\LoggerInterface;

/**
 * @see https://github.com/slimphp/Slim-Skeleton/blob/main/src/Application/Actions/User/UserAction.php
 */
abstract class AbstractAction extends \GrotonSchool\Slim\Actions\AbstractAction
{
    protected DatabaseInterface $database;
    protected ICache $cache;
    protected ICookie $cookie;
    protected ILtiServiceConnector $serviceConnector;
    protected SettingsInterface $settings;

    public function __construct(
        LoggerInterface $logger,
        DatabaseInterface $database,
        ICache $cache,
        ICookie $cookie,
        ILtiServiceConnector $serviceConnector,
        SettingsInterface $settings
    ) {
        parent::__construct($logger);
        $this->database = $database;
        $this->cache = $cache;
        $this->cookie = $cookie;
        $this->serviceConnector = $serviceConnector;
        $this->settings = $settings;
    }
}
