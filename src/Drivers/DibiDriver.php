<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Dibi\Connection;
use Locale\ILocale;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;


/**
 * Class DibiDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
class DibiDriver extends Driver
{
    // define constant table names
    const
        TABLE = 'router',
        TABLE_ALIAS = 'router_alias';

    /** @var string */
    private $tableRouter, $tableRouterAlias;
    /** @var Connection */
    private $connection;
    /** @var Cache */
    private $cache;


    /**
     * DibiDriver constructor.
     *
     * @param string     $prefix
     * @param Connection $connection
     * @param ILocale    $locale
     * @param IStorage   $storage
     */
    public function __construct(string $prefix, Connection $connection, ILocale $locale, IStorage $storage)
    {
        parent::__construct($locale);

        // define table names
        $this->tableRouter = $prefix . self::TABLE;
        $this->tableRouterAlias = $prefix . self::TABLE_ALIAS;

        $this->connection = $connection;
        $this->cache = new Cache($storage, 'AliasRouter-DibiDriver');
    }

//    use SmartObject;
//
//    // define constant table names
//    const
//        TABLE = 'router',
//        TABLE_ALIAS = 'router_alias';
//
//    /** @var string tables name */
//    private $tableRouter, $tableRouterAlias;
//    /** @var Connection database from DI */
//    private $connection;
//    /** @var ILocale locale service */
//    private $locale;
//    /** @var Cache data cache */
//    private $cache;
//    /** @var array domain locale switch */
//    private $domain = [];
//    /** @var bool */
//    private $enabled = true;
//
//
//    /**
//     * Model constructor.
//     *
//     * @param array      $parameters
//     * @param Connection $connection
//     * @param ILocale    $locale
//     * @param IStorage   $storage
//     * @throws Exception
//     */
//    public function __construct(array $parameters, Connection $connection, ILocale $locale, IStorage $storage)
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
//        // define table names
//        $this->tableRouter = $parameters['tablePrefix'] . self::TABLE;
//        $this->tableRouterAlias = $parameters['tablePrefix'] . self::TABLE_ALIAS;
//
//        $this->enabled = boolval($parameters['enabled']);
//
//        $this->connection = $connection;
//        $this->locale = $locale;
//        $this->cache = new Cache($storage, 'AliasRouter-RouterModel');
//    }
//
//
//    /**
//     * Is enabled.
//     *
//     * @return bool
//     */
//    public function isEnabled()
//    {
//        return $this->enabled;
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
//     * Get parameters by locale and alias.
//     *
//     * Use in AliasRouter::match().
//     *
//     * @param $locale
//     * @param $alias
//     * @return mixed
//     * @throws Exception
//     * @throws \Throwable
//     */
//    public function getParametersByAlias($locale, $alias)
//    {
//        $cacheKey = 'getParametersByAlias-' . $locale . '-' . $alias;
//        $result = $this->cache->load($cacheKey);
//        if ($result === null) {
//            $result = $this->connection->select('r.id, r.presenter, r.action, a.id_item')
//                ->from($this->tableRouter)->as('r')
//                ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
//                ->where([
//                    'a.id_locale' => $this->locale->getIdByCode($locale),
//                    'a.alias'     => $alias,
//                ])
//                ->fetch();
////TODO nacitani pole podle aliasu a lokalizace:  locale-alias: [id, presenter, action, id_item] - nacteni vsech kvuli historii
//            $this->cache->save($cacheKey, $result, [
//                Cache::EXPIRE => '30 minutes',
//                Cache::TAGS   => ['router-cache'],
//            ]);
//        }
//        return $result;
//    }
//
//
//    /**
//     * Get alias by presenter and parameters.
//     *
//     * Use in AliasRouter::constructUrl().
//     *
//     * @param $presenter
//     * @param $parameters
//     * @return mixed
//     * @throws Exception
//     * @throws \Throwable
//     */
//    public function getAliasByParameters($presenter, $parameters)
//    {
//        $cacheKey = 'getAliasByParameters-' . $presenter . '-' . serialize($parameters);
//        $result = $this->cache->load($cacheKey);
//        if ($result === null) {
//            $result = $this->connection->select('r.id, a.alias, a.id_item')
//                ->from($this->tableRouter)->as('r')
//                ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
//                ->where([
//                    'r.presenter' => $presenter,
//                    'a.id_locale' => $this->locale->getIdByCode(isset($parameters['locale']) ? $parameters['locale'] : ''),
//                ])
//                ->orderBy('a.added')->desc();
////TODO nacitani podle presenteru, locale, akce a id_item, razeno: nejnovejsi nahore locale-presenter-akce-id_item: [id, alias, id_item] nacitani jen tech co je potreba
//            // add action condition
//            if (isset($parameters['action'])) {
//                $result->where(['r.action' => $parameters['action']]);
//            }
//
//            // add id condition
//            if (isset($parameters['id'])) {
//                $result->where(['a.id_item' => $parameters['id']]);
//            }
//            $result = $result->fetch();
//
//            $this->cache->save($cacheKey, $result, [
//                Cache::EXPIRE => '30 minutes',
//                Cache::TAGS   => ['router-cache'],
//            ]);
//        }
//        return $result;
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
//        $domain = $this->getDomain();
//        // nuluje lokalizaci pri hlavnim jazyku a domain switch
//        if (isset($parameters['locale']) && $parameters['locale'] == $this->locale->getCodeDefault() || ($domain && $domain['switch'])) {
//            return '';
//        }
//        return $parameters['locale'];
//    }
//
//
//    /**
//     * Internal insert and get id route by presenter and action.
//     *
//     * Use in RouterModel::insertAlias().
//     *
//     * @param $presenter
//     * @param $action
//     * @return mixed
//     * @throws Dibi\Exception
//     * @throws Exception
//     * @throws \Throwable
//     */
//    private function getIdRouter($presenter, $action)
//    {
//        $cacheKey = 'getIdRouter-' . $presenter . '-' . $action;
//        $id = $this->cache->load($cacheKey);
//        if ($id === null) {
//            $id = $this->connection->select('id')
//                ->from($this->tableRouter)
//                ->where([
//                    'presenter' => $presenter,
//                    'action'    => $action,
//                ])
//                ->fetchSingle();
//
//            if (!$id) {
//                $id = $this->connection->insert($this->tableRouter, [
//                    'presenter' => $presenter,
//                    'action'    => $action,
//                ])->execute(Dibi::IDENTIFIER);
//            }
//
//            $this->cache->save($cacheKey, $id, [
//                Cache::EXPIRE => '30 minutes',
//                Cache::TAGS   => ['router-cache'],
//            ]);
//        }
//        return $id;
//    }
//
//
//    /**
//     * Internal insert and get id router alias by id router, id locale, id item and alias.
//     *
//     * Use in RouterModel::insertAlias().
//     *
//     * @param $idRouter
//     * @param $idLocale
//     * @param $idItem
//     * @param $alias
//     * @return mixed
//     * @throws Dibi\Exception
//     * @throws Exception
//     * @throws \Throwable
//     */
//    private function getIdRouterAlias($idRouter, $idLocale, $idItem, $alias)
//    {
//        $cacheKey = 'getIdRouterAlias-' . $idRouter . '-' . $idLocale . '-' . $idItem . '-' . $alias;
//        $id = $this->cache->load($cacheKey);
//        if ($id === null) {
//            $cursor = $this->connection->select('id')
//                ->from($this->tableRouterAlias)
//                ->where([
//                    'id_router' => $idRouter,
//                    'id_locale' => $idLocale,
//                    'alias'     => $alias,
//                ]);
//            if ($idItem) {
//                $cursor->where(['id_item' => $idItem]);
//            }
//            $id = $cursor->fetchSingle();
//
//            $this->cache->save($cacheKey, $id, [
//                Cache::EXPIRE => '30 minutes',
//                Cache::TAGS   => ['router-cache'],
//            ]);
//        }
//
//        if (!$id) {
//            try {
//                $id = $this->connection->insert($this->tableRouterAlias, [
//                    'id_router' => $idRouter,
//                    'id_locale' => $idLocale,
//                    'id_item'   => $idItem,
//                    'alias'     => $alias,
//                    'added%sql' => 'NOW()',
//                ])->execute(Dibi::IDENTIFIER);
//            } catch (UniqueConstraintViolationException $e) {
//                // recursive resolve duplicate alias
//                $al = explode('--', $alias);    // explode alias
//                if (count($al) > 1) {
//                    $alias = implode(array_slice($al, 0, -1)) . '--' . ($al[count($al) - 1] + 1);   // implode alias
//                } else {
//                    $alias .= '--' . 1; // first repair name
//                }
//                $id = $this->getIdRouterAlias($idRouter, $idLocale, $idItem, $alias);   // but db autoincrement is still increment :(
//            }
//
//            // promaze cache az po vlozeni
//            $this->cleanCache();
//        }
//        return $id;
//    }
//
//
//    /**
//     * Internal clean cache.
//     */
//    private function cleanCache()
//    {
//        $this->cache->clean([
//            Cache::TAGS => ['router-cache'],
//        ]);
//    }
//
//
//    /**
//     * Manual insert and get id router alias by instnace of presenter and alias string.
//     *
//     * Use in FilterSlug::__invoke().
//     *
//     * @param Presenter $presenter
//     * @param           $alias
//     * @return mixed|null
//     * @throws Dibi\Exception
//     * @throws Exception
//     * @throws \Throwable
//     */
//    public function insertAlias(Presenter $presenter, $alias)
//    {
//        $result = null;
//        $safeAlias = Strings::webalize($alias, '/');    // webalize with ignore /
//        if ($safeAlias) {
//            $idRouter = $this->getIdRouter($presenter->getName(), $presenter->action);
//            $result = $this->getIdRouterAlias($idRouter, $this->locale->getIdByCode($presenter->getParameter('locale')), $presenter->getParameter('id'), $safeAlias);
//        }
//        return $result;
//    }
//
//
//    /**
//     * Create router match.
//     *
//     * @param       $presenter
//     * @param       $action
//     * @param       $alias
//     * @param array $parameters
//     * @return mixed|null
//     * @throws Dibi\Exception
//     * @throws Exception
//     * @throws \Throwable
//     */
//    public function createRouter($presenter, $action, $alias, $parameters = [])
//    {
//        return $this->insertAlias(new InternalRouterPresenter($presenter, $action, $parameters), $alias);
//    }
//
//
//    /**
//     * Delete router match.
//     *
//     * @param null  $presenter
//     * @param null  $action
//     * @param null  $alias
//     * @param array $parameters
//     * @return mixed|null
//     * @throws Dibi\Exception
//     * @throws Exception
//     * @throws \Throwable
//     */
//    public function deleteRouter($presenter = null, $action = null, $alias = null, $parameters = [])
//    {
//        $result = null;
//        if ($presenter && $action && $alias) {
//            $result = $this->connection->delete($this->tableRouterAlias)
//                ->where([
//                    'id_router' => $this->getIdRouter($presenter, $action),
//                    'alias'     => $alias,
//                ]);
//            // dodatecne parametry
//            if ($parameters) {
//                $result->where($parameters);
//            }
//            $result->execute();
//        } elseif ($presenter && $action) {
//            // mazani podle presenteru a akce
//            $this->connection->delete($this->tableRouter)
//                ->where([
//                    'presenter' => $presenter,
//                    'action'    => $action])
//                ->execute();
//        } elseif ($presenter) {
//            // mazani podle presenteru
//            $this->connection->delete($this->tableRouter)
//                ->where(['presenter' => $presenter])
//                ->execute();
//        }
//        // promazani cache
//        $this->cleanCache();
//        return $result;
//    }
//
//
//    /**
//     * Get router alias for tracy.
//     *
//     * Use in Panel::getPanel().
//     *
//     * @param Presenter $presenter
//     * @param           $idLocale
//     * @param null      $idItem
//     * @return mixed
//     */
//    public function getRouterAlias(Presenter $presenter, $idLocale, $idItem = null)
//    {
//        $result = $this->connection->select('a.id, a.alias, a.id_item, a.added')
//            ->from($this->tableRouter)->as('r')
//            ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
//            ->where([
//                'r.presenter' => $presenter->getName(),
//                'a.id_locale' => $idLocale,
//            ])
//            ->orderBy('a.added')->desc();
//
//        // add action condition
//        if ($presenter->action) {
//            $result->where(['r.action' => $presenter->action]);
//        }
//
//        // add id condition
//        if ($idItem) {
//            $result->where(['a.id_item' => $idItem]);
//        }
//        return $result;
//    }
}
