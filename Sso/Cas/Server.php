<?php

namespace BeSimple\SsoAuthBundle\Sso\Cas;

use Symfony\Component\HttpFoundation\RequestStack;
use BeSimple\SsoAuthBundle\Sso\AbstractServer;
use Buzz\Message\Request;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Server extends AbstractServer
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct();

        $this->requestStack = $requestStack;
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        if ($this->getConfigValue('send_locale')) {
            return sprintf(
                '%s?service=%s&_locale=%s',
                parent::getLoginUrl(),
                urlencode($this->getCheckUrl()),
                $this->requestStack->getCurrentRequest()->getLocale()
            );
        }

        return sprintf('%s?service=%s', parent::getLoginUrl(), urlencode($this->getCheckUrl()));
    }

    /**
     * @return string
     */
    public function getLogoutUrl()
    {
        if ($this->getConfigValue('slo')) {
            return parent::getLogoutUrl();
        }

        $url = $this->getLogoutTarget() ? sprintf('&url=%s', urlencode($this->getLogoutTarget())) : null;
        $service = sprintf('service=%s', urlencode($this->getCheckUrl()));

        return sprintf('%s?%s%s', parent::getLogoutUrl(), $service, $url);
    }

    /**
     * @return string
     */
    public function getValidationUrl()
    {
        return sprintf('%s?service=%s', parent::getValidationUrl(), urlencode($this->getCheckUrl()));
    }

    /**
     * @param string $credentials
     *
     * @return \Buzz\Message\Request
     */
    public function buildValidationRequest($credentials)
    {
        $request = new Request();
        $request->fromUrl(sprintf(
            '%s&ticket=%s',
            $this->getValidationUrl(),
            $credentials
        ));

        return $request;
    }

}
