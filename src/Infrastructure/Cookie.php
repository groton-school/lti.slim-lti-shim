<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure;

/**
 * @see https://github.com/packbackbooks/lti-1-3-php-library/wiki/Laravel-Implementation-Guide#cookie Working from Packback's wiki example
 */
class Cookie implements CookieInterface
{
    public function getCookie(string $name): ?string
    {
        return $_COOKIE[$name] ?? null;
    }

    public function setCookie(
        string $name,
        string $value,
        int $exp = 3600,
        array $options = []
    ): void {
        header("Set-Cookie: $name=$value; Max-Age=" . ($options['MaxAge'] ?? $exp) . "; Domain=" . ($options['Domain'] ?? $_SERVER['HTTP_HOST']) . "; Secure; Path=" . ($options['Path'] ?? '/') . "; SameSite=" . ($options['SameSite'] ?? 'None') . "; Partitioned;");
    }

    public function deleteCookie(string $name)
    {
        header("Set-Cookie: $name=; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT");
    }
}
