<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure;

use Packback\Lti1p3\Interfaces\ICookie;

interface CookieInterface extends ICookie
{
    public function deleteCookie(string $name);
}
