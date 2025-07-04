<?php

namespace GrotonSchool\Slim\LTI\Traits;

use Slim\Views\PhpRenderer;

trait ViewsTrait
{
    protected PhpRenderer $slimLtiShimViews;

    protected function initSlmLtiShimViews()
    {
        $this->slimLtiShimViews = new PhpRenderer(__DIR__ . '/../../views');
    }
}
