<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure;

use GrotonSchool\Slim\LTI\Domain\ConsumerConfigurationInterface;

interface CacheInterface
{
    public function cacheConsumerConfiguration(ConsumerConfigurationInterface $config): string;
    public function getConsumerConfiguration(string $identifier): ConsumerConfigurationInterface;
    public function cacheRegistrationToken(string $registration_token): string;
    public function getRegistrationToken(string $identifier): string;
}
