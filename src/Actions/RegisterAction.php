<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Actions;

use GrotonSchool\Slim\LTI\Domain\Registration;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Slim\Http\Response;

class RegisterAction extends AbstractAction
{
    protected function action(): Response
    {
        $openid_configuration = $this->request->getQueryParam('openid_configuration');
        $registration_token = $this->request->getQueryParam('registration_token');
        $client = new Client();
        $response = $client->get($openid_configuration, [
          RequestOptions::HEADERS => [
            'Authorization' => "Bearer $registration_token"
          ]
        ]);
        $config = json_decode($response->getBody()->getContents(), true);
        // FIXME where does the deployment_id come from, and how can I register it?
        /*
         * TODO add configuration hook after specs received from tool consumer
         *   Cache the registration_token and pass $config to a hook for
         *   (possibly interactive) configuration. When the finalized
         *   registration is returned from that hook (or via another hook),
         *   send it on to the registration_endpoint to complete registration.
         */
        $response = $client->post($config['registration_endpoint'], [
          RequestOptions::HEADERS => [
            'Authorization' => "Bearer $registration_token"
          ],
          RequestOptions::JSON => [
            'application_type' => 'web',
            'client_name' => $this->settings->getToolName(),
            'client_uri' => $this->settings->getToolUrl(),
            'grant_types' => ['client_credentials', 'implicit'],
            // TODO make LTI endpoints configurable via abstract settings class?
            'jwks_uri' => "{$this->settings->getToolUrl()}/lti/jwks",
            'token_endpoint_auth_method' => 'private_key_jwt',
            'initiate_login_uri' =>  "{$this->settings->getToolUrl()}/lti/login",
            'redirect_uris' => ["{$this->settings->getToolUrl()}/lti/launch"],
            'response_types' => ['id_token'],
            "scope" => join(' ', $this->settings->getScopes()),
            // TODO does packbackbooks/lti-1p3-tool include any configuration builder tools?
            'https://purl.imsglobal.org/spec/lti-tool-configuration' => [
              'domain' => preg_replace('@^https?://@', '', $this->settings->getToolUrl()),
              'target_link_uri' => "{$this->settings->getToolUrl()}/lti/launch",
              'messages' => [
                [
                  "type" => "LtiResourceLinkRequest",
                  "label" => $this->settings->getToolName(),
                  "custom_parameters" => [
                    "foo" => "bar",
                    "context_id" => '$Context.id'
                  ],
                  "placements" => ["course_navigation"],
                  "roles" => [],
                  "target_link_uri" => "{$this->settings->getToolUrl()}/lti/launch?placement=course_navigation"
                ]
              ],
              "claims" => [
                "sub",
                "iss",
                "name",
                "given_name",
                "family_name",
                "nickname",
                "picture",
                "email",
                "locale"
              ],
              'https://canvas.instructure.com/lti/privacy_level' => 'public'

            ]
          ]
        ]);
        $registration = json_decode($response->getBody()->getContents(), true);
        $this->database->saveRegistration(new Registration(array_merge($registration, $config)));
        $this->response->getBody()->write("<html><body><script>window.parent.postMessage({subject: 'org.imsglobal.lti.close'}, '*');</script></body></html>");
        return $this->response;
    }
}
