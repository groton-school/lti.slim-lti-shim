<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Domain\ConsumerConfigurationInterface;
use Psr\Http\Message\ResponseInterface;

class ConfigurationAction extends AbstractAction implements ConfigurationActionInterface
{
    public function interactiveConfiguration(ConsumerConfigurationInterface $config, string $identifier): ResponseInterface
    {
        return $this->renderer->render($this->response, 'configure.php', [
          'config' => $config,
          'identifier' => $identifier
        ]);
    }

    public function action(): ResponseInterface
    {
        return $this->response->withStatus(403, "Not permitted");
    }
}
