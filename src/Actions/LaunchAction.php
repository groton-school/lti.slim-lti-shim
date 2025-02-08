<?php

declare(strict_types=1);

namespace GrotonSchool\SlimLTI\Actions;

use Packback\Lti1p3\DeepLinkResources\Resource;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface as Response;

class LaunchAction extends LTIAction
{
    protected function action(): Response
    {
        $launch = LtiMessageLaunch::new(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        )->initialize($this->request->getParsedBody());

        if ($launch->isDeepLinkLaunch()) {
            // TODO deep link
        } elseif ($launch->isResourceLaunch()) {
            // TODO resource
        } elseif ($launch->isSubmissionReviewLaunch()) {
            // TODO submission review
        }

        return $this->response;
    }
}
