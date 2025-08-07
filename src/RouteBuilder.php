<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI;

use GrotonSchool\Slim\LTI\Actions\JWKSAction;
use GrotonSchool\Slim\LTI\Actions\LaunchAction;
use GrotonSchool\Slim\LTI\Actions\LoginAction;
use GrotonSchool\Slim\LTI\Actions\RegistrationStartAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;

class RouteBuilder
{
    public static function define(App $app): RouteGroupInterface
    {
        return $app->group('/lti', function (RouteCollectorProxyInterface $lti) {
            $lti->post('/launch', LaunchAction::class);
            $lti->get('/jwks', JWKSAction::class);
            $lti->get('/register', RegistrationStartAction::class);
            $lti->post('/login', LoginAction::class);
        });
    }
}
