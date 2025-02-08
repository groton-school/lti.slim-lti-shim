<?php

declare(strict_types=1);

namespace GrotonSchool\SlimLTI\Actions;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface as Response;

class RegisterAction extends LTIAction
{
    protected function action(): Response
    {
        $params = $this->request->getParsedBody();
        $client = new Client();
        $tc_profile_url = $params['tc_profile_url'];
        $reg_key = $params['reg_key'];
        $reg_password = $params['reg_password'];
        $launch_presentation_return_url =
            $params['launch_presentation_return_url'];
        $response = $client->get($tc_profile_url);
        $this->response->getBody()->write($response->getBody()->getContents());
        return $this->response;
    }
}
