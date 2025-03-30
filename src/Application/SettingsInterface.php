<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Application;

interface SettingsInterface
{
    public function getToolUrl(): string;
    public function getToolName(): string;
    /** @return string[] */
    public function getScopes(): array;
}
