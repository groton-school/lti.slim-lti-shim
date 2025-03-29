<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure;

use GrotonSchool\Slim\LTI\Domain\Registration;
use Packback\Lti1p3\Interfaces\IDatabase;

interface DatabaseInterface extends IDatabase
{
    public function saveRegistration(Registration $registration): void;
}
