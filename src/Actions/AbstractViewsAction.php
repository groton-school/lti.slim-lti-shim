<?php

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use Slim\Views\PhpRenderer;

abstract class AbstractViewsAction extends AbstractAction
{
    protected PhpRenderer $views;

    public function __construct()
    {
        $this->views = new PhpRenderer(__DIR__ . '/../../views');
    }
}
