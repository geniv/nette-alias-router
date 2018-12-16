<?php declare(strict_types=1);

namespace AliasRouter;

use AliasRouter\Drivers\IDriver;
use Nette\Application\IRouter;
use Nette\Application\Request;
use Nette\Http\IRequest;
use Nette\Http\Url;


/**
 * Class Router
 *
 * @author  geniv
 * @package AliasRouter
 */
class Router implements IRouter
{
    /** @var IDriver */
    private $driver;
    /** @var array */
    private $domainAlias, $defaultParameters, $paginatorVariable;
    /** @var bool */
    private $oneWay, $secure;


    /**
     * Router constructor.
     *
     * @param IAliasRouter $aliasRouter
     */
    public function __construct(IAliasRouter $aliasRouter)
    {
        $this->driver = $aliasRouter->getDriver();

        $this->domainAlias = $aliasRouter->getDomainAlias();
        $this->defaultParameters = $aliasRouter->getDefaultParameters();
        $this->paginatorVariable = $aliasRouter->getPaginatorVariable();
        $this->oneWay = $aliasRouter->isOneWay();
        $this->secure = $aliasRouter->isSecure();
    }


    /**
     * Maps HTTP request to a Request object.
     *
     * @param IRequest $httpRequest
     * @return Request|null
     */
    public function match(IRequest $httpRequest)
    {
        $pathInfo = $httpRequest->getUrl()->getPathInfo();

        // parse locale
        $locale = $this->defaultParameters['locale'];
        if (preg_match('/((?<locale>[a-z]{2})\/)?/', $pathInfo, $m) && isset($m['locale'])) {
            $locale = trim($m['locale'], '/_');
            $pathInfo = trim(substr($pathInfo, strlen($m['locale'])), '/_');   // clean slug
        }

        // select locale by domain
        if ($this->domainAlias) {
            $host = $httpRequest->url->host;    // get url host for select by locale
            if (isset($this->domainAlias[$host])) {
                $locale = $this->domainAlias[$host];
            }
        }

        // parse alias
        $alias = null;
        if (preg_match('/((?<alias>[a-z0-9-\/]+)(\/)?)?/', $pathInfo, $m) && isset($m['alias'])) {
            $alias = trim($m['alias'], '/_');
            $pathInfo = trim(substr($pathInfo, strlen($m['alias'])), '/_');   // clean locale from slug
        }

        // parse paginator
        $parameters = [];
        if (preg_match('/((?<vp>[a-z0-9-]+)(\/)?)?/', $pathInfo, $m) && isset($m['vp'])) {
            $parameters[$this->paginatorVariable] = trim($m['vp'], '/_');
        }

        // set default presenter
        $presenter = $this->defaultParameters['presenter'];

        // set locale to parameters
        $parameters['locale'] = $locale;

        // clean alias from last slug
        if ($alias) {
            $alias = rtrim($alias, '/_');
        }

        if ($alias) {
            // load parameters
            $param = $this->driver->getParametersByAlias($locale, $alias);
            if ($param) {
                $presenter = $param['presenter'];
                $parameters['action'] = $param['action'];
                if ($param['id_item']) {
                    $parameters['id'] = $param['id_item'];
                }
            } else {
                return null;
            }
        }

        $parameters += $httpRequest->getQuery();

        if (!$presenter) {
            return null;
        }

        return new Request(
            $presenter,
            $httpRequest->getMethod(),
            $parameters,
            $httpRequest->getPost(),
            $httpRequest->getFiles(),
            [Request::SECURED => $httpRequest->isSecured()]
        );
    }


    /**
     * Constructs absolute URL from Request object.
     *
     * @param Request $appRequest
     * @param Url     $refUrl
     * @return string|null
     */
    public function constructUrl(Request $appRequest, Url $refUrl)
    {
        // in one way mode or ignore ajax request
        if ($this->oneWay || isset($appRequest->parameters['do'])) {
            return null;
        }

        $urlAlias = $this->driver->getAliasByParameters($appRequest->presenterName, $appRequest->parameters);
        if ($urlAlias) {
            $parameters = $appRequest->parameters;

            $part = implode('/', array_filter([$this->driver->getCodeLocale($parameters, $this->domainAlias), $urlAlias]));
            $alias = trim(isset($parameters[$this->paginatorVariable]) ? implode('_', [$part, $parameters[$this->paginatorVariable]]) : $part, '/_');

            unset($parameters['locale'], $parameters['action'], $parameters['alias'], $parameters['id'], $parameters[$this->paginatorVariable]);

            // create url address
            $url = new Url($refUrl->getBaseUrl() . $alias);
            $url->setScheme($this->secure ? 'https' : 'http');
            $url->setQuery($parameters);
            return $url->getAbsoluteUrl();
        } else {
            // if domain alias active then skip FORWARD method metodu or Homepage presenter - remove locale from url
            if ($this->domainAlias && ($appRequest->method != 'FORWARD' || $appRequest->presenterName == 'Homepage')) {
                $url = new Url($refUrl->getBaseUrl());  // create base path without parameter
                $url->setScheme($this->secure ? 'https' : 'http');
                return $url->getAbsoluteUrl();
            }
        }
        return null;
    }
}
