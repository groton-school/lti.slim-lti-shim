<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI;

use GrotonSchool\Slim\LTI\Actions\RegisterAction;
use GrotonSchool\Slim\LTI\Actions\JWKSAction;
use GrotonSchool\Slim\LTI\Actions\LaunchAction;
use GrotonSchool\Slim\LTI\Actions\LoginAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;

class Routes
{
    public static function register(App $app)
    {
        $app->group('/lti', function (Group $lti) {
            $lti->post('/launch', LaunchAction::class);
            $lti->get('/jwks', JWKSAction::class);
            $lti->get('/register', RegisterAction::class);
            $lti->post('/login', LoginAction::class);
        });
    }
};
