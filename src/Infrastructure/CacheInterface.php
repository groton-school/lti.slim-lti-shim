<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure;

use JsonSerializable;
use Packback\Lti1p3\Interfaces\ICache;

interface CacheInterface extends ICache
{
    public const OPENID_CONFIGURATION = 'openid_configuration';
    public const REGISTRATION_TOKEN = 'registration_token';

    /**
     * @param JsonSerializable $config
     * @param string $registration_token
     * @return string Unique Id to retrieve data
     */
    public function cacheRegistrationConfiguration(
        JsonSerializable $config,
        string $registration_token
    ): string;

    /**
     * @param string $uniqueId
     * @return array{[self::OPENID_CONFIGURATION]: JsonSerializable, [self::REGISTRATION_TOKEN]: string}
     */
    public function getRegistrationConfiguration(string $uniqueId): array;
}
