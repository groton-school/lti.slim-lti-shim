<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Middleware;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use Exception;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @see https://github.com/packbackbooks/lti-1-3-php-library/wiki/Laravel-Implementation-Guide#cookie Working from Packback's wiki example
 */
class CookieMiddleware implements CookieInterface, MiddlewareInterface
{
    private ?ServerRequestInterface $request = null;

    /** @var array<string,SetCookie> */
    private array $cookies = [];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->request = $request;
        $response = $handler->handle($request);

        foreach ($this->cookies as $cookie) {
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;
    }

    public function getCookie(string $name): ?string
    {
        return FigRequestCookies::get($this->request, $name)->getValue();
    }

    public function setCookie(
        string $name,
        string $value,
        int $exp = 3600,
        array $options = []
    ): void {
        $this->cookies[$name] = SetCookie::create($name)
            ->withValue($value)
            ->withExpires($exp)
            ->withPath('/')
            ->withSameSite(SameSite::none())
            ->withSecure()
            ->withPartitioned();

        foreach ($options as $key => $option) {
            switch (strtolower($key)) {
                case 'maxage':
                    $this->cookies[$name] = $this->cookies[$name]->withMaxAge($option);
                    break;
                case 'domain':
                    $this->cookies[$name] = $this->cookies[$name]->withDomain($option);
                    break;
                case 'path':
                    $this->cookies[$name] = $this->cookies[$name]->withPath($option);
                    break;
                case 'samesite':
                    $this->cookies[$name] = $this->cookies[$name]->withSameSite($option);
                    break;
                case 'secure':
                    $this->cookies[$name] = $this->cookies[$name]->withSecure($option);
                    break;
                case 'httponly':
                    $this->cookies[$name] = $this->cookies[$name]->withHttpOnly($option);
                    break;
            }
        }
    }

    public function deleteCookie(string $name)
    {
        $this->setCookie($name, '');
        $this->cookies[$name] = $this->cookies[$name]->expire();
    }
}
