<?php declare(strict_types=1);

namespace AliasRouter;

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

    /**
     * Router constructor.
     *
     * @param IAliasRouter $aliasRouter
     */
    public function __construct(IAliasRouter $aliasRouter)
    {
        //FIXME sem pride IDriver!!!
//        dump($aliasRouter);
    }


    /**
     * Maps HTTP request to a Request object.
     *
     * @return Request|null
     */
    function match(IRequest $httpRequest)
    {
        // TODO: Implement match() method.
    }


    /**
     * Constructs absolute URL from Request object.
     *
     * @return string|null
     */
    function constructUrl(Request $appRequest, Url $refUrl)
    {
        // TODO: Implement constructUrl() method.
    }



//    /**
//     * Maps HTTP request to a Request object.
//     *
//     * @param IRequest $httpRequest
//     * @return Request|NULL
//     * @throws \Exception
//     * @throws \Throwable
//     */
//    public function match(IRequest $httpRequest)
//    {
//        $pathInfo = $httpRequest->getUrl()->getPathInfo();
//
//        // parse locale
//        $locale = $this->defaultParameters['locale'];
//        if (preg_match('/((?<locale>[a-z]{2})\/)?/', $pathInfo, $m) && isset($m['locale'])) {
//            $locale = trim($m['locale'], '/_');
//            $pathInfo = trim(substr($pathInfo, strlen($m['locale'])), '/_');   // ocesani slugu
//        }
//
//        // vyber jazyka podle domeny
//        $domain = $this->routerModel->getDomain();
//        if ($domain && $domain['switch']) {
//            $host = $httpRequest->url->host;    // nacteni url hostu pro zvoleni jazyka
//            if (isset($domain['alias'][$host])) {
//                $locale = $domain['alias'][$host];
//            }
//        }
//
//        // parse alias
//        $alias = null;
//        if (preg_match('/((?<alias>[a-z0-9-\/]+)(\/)?)?/', $pathInfo, $m) && isset($m['alias'])) {
//            $alias = trim($m['alias'], '/_');
//            $pathInfo = trim(substr($pathInfo, strlen($m['alias'])), '/_');   // ocesani jazyka od slugu
//        }
//
//        // parse paginator
//        $parameters = [];
//        if (preg_match('/((?<vp>[a-z0-9-]+)(\/)?)?/', $pathInfo, $m) && isset($m['vp'])) {
//            $parameters[$this->paginatorVariable] = trim($m['vp'], '/_');
//        }
//
//        // set default presenter
//        $presenter = $this->defaultParameters['presenter'];
//
//        // set locale to parameters
//        $parameters['locale'] = $locale;
//
//        // akceptace adresy kde je na konci zbytecne lomitko, odebere posledni lomitko
//        if ($alias) {
//            $alias = rtrim($alias, '/_');
//        }
//
//        if ($alias) {
//            // load parameters from database
//            $param = $this->routerModel->getParametersByAlias($locale, $alias);
//            if ($param) {
//                $presenter = $param->presenter;
//                $parameters['action'] = $param->action;
//                if ($param->id_item) {
//                    $parameters['id'] = $param->id_item;
//                }
//            } else {
//                return null;
//            }
//        }
//
//        $parameters += $httpRequest->getQuery();
//
//        if (!$presenter) {
//            return null;
//        }
//
//        return new Request(
//            $presenter,
//            $httpRequest->getMethod(),
//            $parameters,
//            $httpRequest->getPost(),
//            $httpRequest->getFiles(),
//            [Request::SECURED => $httpRequest->isSecured()]
//        );
//    }
//
//
//    /**
//     * Constructs absolute URL from Request object.
//     *
//     * @param Request $appRequest
//     * @param Url     $refUrl
//     * @return NULL|string
//     * @throws \Exception
//     * @throws \Throwable
//     */
//    public function constructUrl(Request $appRequest, Url $refUrl)
//    {
//        // in one way mode or ignore ajax request
//        if ($this->oneWay || isset($appRequest->parameters['do'])) {
//            return null;
//        }
//
//        $param = $this->routerModel->getAliasByParameters($appRequest->presenterName, $appRequest->parameters);
//        if ($param) {
//            $parameters = $appRequest->parameters;
//
//            $part = implode('/', array_filter([$this->routerModel->getCodeLocale($parameters), $param->alias]));
//            $alias = trim(isset($parameters[$this->paginatorVariable]) ? implode('_', [$part, $parameters[$this->paginatorVariable]]) : $part, '/_');
//
//            unset($parameters['locale'], $parameters['action'], $parameters['alias'], $parameters['id'], $parameters[$this->paginatorVariable]);
//
//            // create url address
//            $url = new Url($refUrl->getBaseUrl() . $alias);
//            $url->setScheme($this->secure ? 'https' : 'http');
//            $url->setQuery($parameters);
//            return $url->getAbsoluteUrl();
//        } else {
//            // vyber jazyka podle domeny
//            $domain = $this->routerModel->getDomain();
//            // pokud je aktivni detekce podle domeny tak preskakuje FORWARD metodu nebo Homepage presenter
//            // jde o vyhazovani lokalizace na HP pri zapnutem domain switch
//            if ($domain && $domain['switch'] && ($appRequest->method != 'FORWARD' || $appRequest->presenterName == 'Homepage')) {
//                $url = new Url($refUrl->getBaseUrl());  // vytvari zakladni cestu bez parametru
//                $url->setScheme($this->secure ? 'https' : 'http');
//                return $url->getAbsoluteUrl();
//            }
//        }
//        return null;
//    }
}