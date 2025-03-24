<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use App\Application\Actions\Action;
use App\Application\Settings\SettingsInterface;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\IDatabase;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Psr\Log\LoggerInterface;

/**
 * @see https://github.com/slimphp/Slim-Skeleton/blob/main/src/Application/Actions/User/UserAction.php
 */
abstract class LTIAction extends Action
{
    public const PROJECT_URL = 'projectUrl';

    protected IDatabase $database;
    protected ICache $cache;
    protected ICookie $cookie;
    protected ILtiServiceConnector $serviceConnector;
    protected string $projectUrl;

    public function __construct(
        LoggerInterface $logger,
        IDatabase $database,
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
        $this->projectUrl = $settings->get(self::PROJECT_URL);
    }
}
