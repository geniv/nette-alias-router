<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Locale\ILocale;


/**
 * Class ArrayDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
class ArrayDriver extends Driver
{

    /**
     * ArrayDriver constructor.
     *
     * @param ILocale $locale
     */
    public function __construct(ILocale $locale)
    {
        parent::__construct($locale);
    }

    //TODO bude podobne jako: vendor/geniv/nette-locale/src/Drivers/ArrayDriver.php ale bude vychazet ze static routeru!

//    use SmartObject;
//
//    /** @var bool default inactive https */
//    private $secure = false;
//    /** @var bool default inactive one way router */
//    private $oneWay = false;
//    /** @var array default parameters */
//    private $defaultParameters = [];
//    /** @var string paginator variable */
//    private $praginatorVariable = 'vp';
//    /** @var array domain locale switch */
//    private $domain = [];
//
//    /** @var array */
//    private $route = [];
//    /** @var ILocale */
//    private $locale = null;
//
//
//    /**
//     * StaticRouter constructor.
//     *
//     * @param array   $parameters
//     * @param ILocale $locale
//     * @throws Exception
//     */
//    public function __construct(array $parameters, ILocale $locale)
//    {
//        // pokud jeden z parametru domainSwitch nebo domainAlias neexistuje
//        if (isset($parameters['domainSwitch']) XOR isset($parameters['domainAlias'])) {
//            throw new Exception('Domain switch or domain alias is not defined in configure! ([domainSwitch: true, domainAlias: [cs: example.cz]])');
//        }
//
//        // nacteni domain nastaveni
//        if (isset($parameters['domainSwitch']) && isset($parameters['domainAlias'])) {
//            $this->domain = [
//                'switch' => $parameters['domainSwitch'],
//                'alias'  => $parameters['domainAlias'],
//            ];
//        }
//
//        $this->route = $parameters['route'];
//        $this->locale = $locale;
//    }
//
//
//    /**
//     * Enable https, defalt is disable.
//     *
//     * @param bool $secure
//     * @return $this
//     */
//    public function setSecure($secure)
//    {
//        $this->secure = $secure;
//        return $this;
//    }
//
//
//    /**
//     * Enable one way router.
//     *
//     * @param $oneWay
//     * @return $this
//     */
//    public function setOneWay($oneWay)
//    {
//        $this->oneWay = $oneWay;
//        return $this;
//    }
//
//
//    /**
//     * Set default parameters, presenter, action and locale.
//     *
//     * @param $presenter
//     * @param $action
//     * @param $locale
//     * @return $this
//     */
//    public function setDefaultParameters($presenter, $action, $locale)
//    {
//        $this->defaultParameters = [
//            'presenter' => $presenter,
//            'action'    => $action,
//            'locale'    => $locale,
//        ];
//        return $this;
//    }
//
//
//    /**
//     * Set paginator variable.
//     *
//     * @param $variable
//     * @return $this
//     */
//    public function setPaginatorVariable($variable)
//    {
//        $this->praginatorVariable = $variable;
//        return $this;
//    }
//
//
//    /**
//     * Get array url domains.
//     *
//     * Use in AliasRouter::match().
//     *
//     * @return array
//     */
//    public function getDomain()
//    {
//        return $this->domain;
//    }
//
//
//    /**
//     * Get locale code, empty code for default locale.
//     *
//     * Use in AliasRouter::constructUrl().
//     *
//     * @param $parameters
//     * @return string
//     */
//    public function getCodeLocale(array $parameters)
//    {
//        // null locale => empty locale in url
//        if (!isset($parameters['locale'])) {
//            return '';
//        }
//
//        // nuluje lokalizaci pri hlavnim jazyku a domain switch
//        if (isset($parameters['locale']) && $parameters['locale'] == $this->locale->getCodeDefault() || ($this->domain && $this->domain['switch'])) {
//            return '';
//        }
//        return $parameters['locale'];
//    }
//
//
//    /**
//     * Maps HTTP request to a Request object.
//     *
//     * @param IRequest $httpRequest
//     * @return Request|null
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
//        $domain = $this->domain['alias'];
//        if ($this->domain && $this->domain['switch']) {
//            $host = $httpRequest->url->host;    // nacteni url hostu pro zvoleni jazyka
//            if (isset($domain[$host])) {
//                $locale = $domain[$host];
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
//            $parameters[$this->praginatorVariable] = trim($m['vp'], '/_');
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
//            if (isset($this->route[$locale])) {
//                if (isset($this->route[$locale][$alias])) {
//                    list($presenter, $action) = explode(':', $this->route[$locale][$alias]);
//                    $parameters['action'] = $action;
//                } else {
//                    return null;
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
//     * @return null|string
//     */
//    public function constructUrl(Request $appRequest, Url $refUrl)
//    {
//        // in one way mode or ignore ajax request
//        if ($this->oneWay || isset($appRequest->parameters['do'])) {
//            return null;
//        }
//
//        $parameters = $appRequest->parameters;
//
//        $locale = (isset($parameters['locale']) ? $parameters['locale'] : '');
//        $presenterAction = $appRequest->presenterName . ':' . (isset($parameters['action']) ? $parameters['action'] : '');
//
//        if (isset($this->route[$locale]) && ($row = array_search($presenterAction, $this->route[$locale], true)) != null) {
//            $part = implode('/', array_filter([$this->getCodeLocale($parameters), $row]));
//            $alias = trim(isset($parameters[$this->praginatorVariable]) ? implode('_', [$part, $parameters[$this->praginatorVariable]]) : $part, '/_');
//
//            unset($parameters['locale'], $parameters['action'], $parameters['alias'], $parameters['id'], $parameters[$this->praginatorVariable]);
//
//            // create url address
//            $url = new Url($refUrl->getBaseUrl() . $alias);
//            $url->setScheme($this->secure ? 'https' : 'http');
//            $url->setQuery($parameters);
//            return $url->getAbsoluteUrl();
//        } else {
//            // vyber jazyka podle domeny
//            // pokud je aktivni detekce podle domeny tak preskakuje FORWARD metodu nebo Homepage presenter
//            // jde o vyhazovani lokalizace na HP pri zapnutem domain switch
//            if ($this->domain && $this->domain['switch'] && ($appRequest->method != 'FORWARD' || $appRequest->presenterName == 'Homepage')) {
//                $url = new Url($refUrl->getBaseUrl());  // vytvari zakladni cestu bez parametru
//                $url->setScheme($this->secure ? 'https' : 'http');
//                return $url->getAbsoluteUrl();
//            }
//        }
//        return null;
//    }


//    // define constant table names
//    const
//        TABLE_NAME = 'configurator',
//        TABLE_NAME_IDENT = 'configurator_ident';
//
//    /** @var Connection */
//    private $connection;
//    /** @var string */
//    private $tableConfigurator, $tableConfiguratorIdent;
//    /** @var Cache */
//    private $cache;
//
//
//    /**
//     * DibiDriver constructor.
//     *
//     * @param string     $prefix
//     * @param Connection $connection
//     * @param ILocale    $locale
//     * @param IStorage   $storage
//     */
//    public function __construct(string $prefix, Connection $connection, ILocale $locale, IStorage $storage)
//    {
//        parent::__construct($locale);
//
//        // define table names
//        $this->tableConfigurator = $prefix . self::TABLE_NAME;
//        $this->tableConfiguratorIdent = $prefix . self::TABLE_NAME_IDENT;
//
//        $this->connection = $connection;
//        $this->cache = new Cache($storage, 'Configurator-DibiDriver');
//    }
//
//
//    /**
//     * Get list data.
//     *
//     * @param int|null $idLocale
//     * @return IDataSource
//     */
//    public function getListData(int $idLocale = null): IDataSource
//    {
//        $result = $this->connection->select('c.id, c.id_ident, ci.ident, ci.type, ' .
//            'IFNULL(lo_c.id_locale, c.id_locale) id_locale, ' .
//            'IFNULL(lo_c.content, c.content) content, ' .
//            'IFNULL(lo_c.enable, c.enable) enable')
//            ->from($this->tableConfiguratorIdent)->as('ci')
//            ->join($this->tableConfigurator)->as('c')->on('c.id_ident=ci.id')->and(['c.id_locale' => $this->idDefaultLocale])
//            ->leftJoin($this->tableConfigurator)->as('lo_c')->on('lo_c.id_ident=ci.id')->and(['lo_c.id_locale' => $idLocale ?: $this->locale->getId()]);
//        return $result;
//    }
//
//
//    /**
//     * Edit data.
//     *
//     * @param int   $id
//     * @param array $values
//     * @return int
//     * @throws \Dibi\Exception
//     */
//    public function editData(int $id, array $values): int
//    {
//        $result = $this->connection->update($this->tableConfigurator, $values)
//            ->where(['id' => $id]);
//        return $result->execute(Dibi::AFFECTED_ROWS);
//    }
//
//
//    /**
//     * Delete data.
//     *
//     * @param int $id
//     * @return int
//     * @throws \Dibi\Exception
//     */
//    public function deleteData(int $id): int
//    {
//        $result = $this->connection->delete($this->tableConfigurator)
//            ->where(['id' => $id]);
//        return $result->execute(Dibi::AFFECTED_ROWS);
//    }
//
//
//    /**
//     * Clean cache.
//     */
//    public function cleanCache()
//    {
//        // internal clean cache
//        $this->cache->clean([
//            Cache::TAGS => ['loadData'],
//        ]);
//    }
//
//
//    /**
//     * Save internal data.
//     *
//     * @internal
//     * @param string $type
//     * @param string $identification
//     * @param string $content
//     * @return int
//     * @throws \Dibi\Exception
//     */
//    protected function saveInternalData(string $type, string $identification, string $content = ''): int
//    {
//        $result = 0;
//        // check exist configure id
//        $conf = $this->connection->select('id')
//            ->from($this->tableConfiguratorIdent)
//            ->where(['ident' => $identification])
//            ->fetchSingle();
//
//        if (!$conf) {
//            $idIdentification = $this->connection->insert($this->tableConfiguratorIdent, [
//                'ident' => $identification, 'type' => $type,
//            ])->execute(Dibi::IDENTIFIER);
//
//            // insert data
//            $values = [
//                'id_locale' => $this->idDefaultLocale,  // UQ 1/2 - always default create language
//                'id_ident'  => $idIdentification,       // UQ 2/2
//                'content'   => ($content ?: $this->getDefaultContent($type, $identification)),
//                'enable'    => true,                    // always default enabled
//            ];
//            // only insert data
//            $result = $this->connection->insert($this->tableConfigurator, $values)->execute(Dibi::IDENTIFIER);
//        } else {
//            // if not empty value - in case first {control ...} in web
//            if ($content) {
//                // update data
//                $result = $this->connection->update($this->tableConfigurator, [
//                    'content' => $content,
//                ])->where(['id' => $conf])->execute(Dibi::AFFECTED_ROWS);
//            }
//        }
//        $this->cleanCache();
//        return $result;
//    }
//
//
//    /**
//     * Load internal data.
//     *
//     * @internal
//     */
//    protected function loadInternalData()
//    {
//        $cacheKey = 'loadInternalData' . $this->locale->getId();
//        $this->values = $this->cache->load($cacheKey);
//        if ($this->values === null) {
//            $this->values = $this->getListData()->fetchAssoc('ident');
//            try {
//                $this->cache->save($cacheKey, $this->values, [
//                    Cache::TAGS => ['loadData'],
//                ]);
//            } catch (\Throwable $e) {
//            }
//        }
//
//        // process default content
//        $this->searchDefaultContent();
//    }
}