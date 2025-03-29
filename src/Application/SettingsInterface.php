<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Application;

interface SettingsInterface
{
    public function getUrl(): string;
    public function getName(): string;
    /** @return string[] */
    public function getScopes(): array;
}
