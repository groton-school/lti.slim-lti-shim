<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use Exception;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface;

class LaunchAction extends AbstractAction
{
    protected function action(): ResponseInterface
    {
        $launch = LtiMessageLaunch::new(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        )->initialize($this->request->getParsedBody());


        if ($launch->isDeepLinkLaunch()) {
            $this->response->getBody()->write("<h1>Deep Link</h1>");
            // TODO deep link
        } elseif ($launch->isResourceLaunch()) {
            $this->response->getBody()->write("<h1>Resource</h1>");
            // TODO resource
        } elseif ($launch->isSubmissionReviewLaunch()) {
            $this->response->getBody()->write("<h1>Submission Review</h1>");
            // TODO submission review
        }

        $data = json_encode($launch->getLaunchData(), JSON_PRETTY_PRINT);
        $this->response->getBody()->write("<pre>$data</pre>");

        return $this->response;
    }
}
