<?php

namespace BeSimple\SsoAuthBundle\Sso;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Trait UrlGeneratorTrait.
 * 
 */
trait UrlGeneratorTrait
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $path
     * @return string
     */
    public function generateUrl(Request $request, $path)
    {
        if('/' !== $path[0]) {
            return $this->urlGenerator->generate(
                $path,
                $request->attributes->get('_route_params'),
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }
        return $request->getUriForPath($path);
    }
}