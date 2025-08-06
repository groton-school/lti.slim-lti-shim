<?php

namespace GrotonSchool\Slim\LTI\Actions;

use Slim\Views\PhpRenderer;

class AbstractViewsAction
{
    protected PhpRenderer $views;

    public function __construct()
    {
        $this->views = new PhpRenderer(__DIR__ . '/../../views');
    }
}
