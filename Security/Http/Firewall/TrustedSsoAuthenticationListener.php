<?php

namespace BeSimple\SsoAuthBundle\Security\Http\Firewall;

use BeSimple\SsoAuthBundle\Sso\UrlGeneratorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use BeSimple\SsoAuthBundle\Sso\Factory;


class TrustedSsoAuthenticationListener extends AbstractAuthenticationListener
{
    use UrlGeneratorTrait;

    private $factory;

    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
    }

    protected function attemptAuthentication(Request $request)
    {
        $manager = $this->factory->getManager(
            $this->options['manager'],
            $this->generateUrl($request, $this->options['check_path'])
        );

        if (!$manager->getProtocol()->isValidationRequest($request)) {
            return null;
        }

        return $this->authenticationManager->authenticate($manager->createToken($request));
    }
}
