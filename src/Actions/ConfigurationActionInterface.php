<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Domain\ConsumerConfigurationInterface;
use Psr\Http\Message\ResponseInterface;

interface ConfigurationActionInterface
{
    public function interactiveConfiguration(ConsumerConfigurationInterface $config): ResponseInterface;
}
