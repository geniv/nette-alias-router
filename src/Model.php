<?php

namespace AliasRouter;

use Dibi;
use Dibi\Connection;
use Exception;
use Locale\Locale;
use Nette\Application\UI\Presenter;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\SmartObject;
use Nette\Utils\Strings;


/**
 * Class Model
 *
 * @author  geniv
 * @package AliasRouter
 */
class Model
{
    use SmartObject;

    /** @var string tables name */
    private $tableRouter, $tableRouterAlias;
    /** @var Connection database from DI */
    private $connection;
    /** @var LocaleService locale service */
    private $localeService;
    /** @var Cache data cache */
    private $cache;
    /** @var array domain locale switch */
    private $domain = [];


    /**
     * Model constructor.
     *
     * @param array      $parameters
     * @param Connection $connection
     * @param Locale     $locale
     * @param IStorage   $storage
     * @throws Exception
     */
    public function __construct(array $parameters, Connection $connection, Locale $locale, IStorage $storage)
    {
        // pokud parametr table neexistuje
        if (!isset($parameters['table'])) {
            throw new Exception('Table name is not defined in configure! (table: xy)');
        }

        // nacteni jmena tabulky
        $tableRouter = $parameters['table'];

        // pokud jeden z parametru domainSwitch nebo domainAlias neexistuje
        if (isset($parameters['domainSwitch']) XOR isset($parameters['domainAlias'])) {
            throw new Exception('Domain switch or domain alias is not defined in configure! ([domainSwitch: true, domainAlias: [cs: example.cz]])');
        }

        // nacteni domain nastaveni
        if (isset($parameters['domainSwitch']) && isset($parameters['domainAlias'])) {
            $this->domain = [
                'switch' => $parameters['domainSwitch'],
                'alias'  => $parameters['domainAlias'],
            ];
        }

        $this->tableRouter = $tableRouter;
        $this->tableRouterAlias = $tableRouter . '_alias';
        $this->connection = $connection;
        $this->localeService = $locale;
        $this->cache = new Cache($storage, 'cache-AliasRouter-Model');
    }


    /**
     * Get array url domains.
     *
     * Use in Router::match().
     *
     * @return array
     */
    public function getDomain()
    {
        return $this->domain;
    }


    /**
     * Get parameters by locale and alias.
     *
     * Use in Router::match().
     *
     * @param $locale
     * @param $alias
     * @return mixed
     */
    public function getParametersByAlias($locale, $alias)
    {
        $cacheKey = 'getParametersByAlias-' . $locale . '-' . $alias;
        $result = $this->cache->load($cacheKey);
        if ($result === null) {
            $result = $this->connection->select('r.id, r.presenter, r.action, a.id_item')
                ->from($this->tableRouter)->as('r')
                ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
                ->where(['a.id_locale%iN' => $this->localeService->getIdByCode($locale)])
                ->where('a.alias=%s', $alias)
                ->fetch();

            $this->cache->save($cacheKey, $result, [
                Cache::EXPIRE => '30 minutes',
                Cache::TAGS   => ['router-cache'],
            ]);
        }
        return $result;
    }


    /**
     * Get alias by presenter and parameters.
     *
     * Use in Router::constructUrl().
     *
     * @param $presenter
     * @param $parameters
     * @return mixed
     */
    public function getAliasByParameters($presenter, $parameters)
    {
        $cacheKey = 'getAliasByParameters-' . $presenter . '-' . serialize($parameters);
        $result = $this->cache->load($cacheKey);
        if ($result === null) {
            $result = $this->connection->select('r.id, a.alias, a.id_item')
                ->from($this->tableRouter)->as('r')
                ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
                ->where('r.presenter=%s', $presenter)
                ->where(['a.id_locale%iN' => $this->localeService->getIdByCode(isset($parameters['locale']) ? $parameters['locale'] : null)])
                ->orderBy('a.added')->desc();

            // add action condition
            if (isset($parameters['action'])) {
                $result->where('r.action=%s', $parameters['action']);
            }

            // add id condition
            if (isset($parameters['id'])) {
                $result->where('a.id_item=%i', $parameters['id']);
            }
            $result = $result->fetch();

            $this->cache->save($cacheKey, $result, [
                Cache::EXPIRE => '30 minutes',
                Cache::TAGS   => ['router-cache'],
            ]);
        }
        return $result;
    }


    /**
     * Get locale code, empty code for default locale.
     *
     * Use in Router::constructUrl().
     *
     * @param $parameters
     * @return string
     */
    public function getCodeLocale($parameters)
    {
        $domain = $this->getDomain();
        // nuluje lokalizaci pri hlavnim jazyku a domain switch
        if (isset($parameters['locale']) && $parameters['locale'] == $this->localeService->getCodeDefault() || ($domain && $domain['switch'])) {
            return '';
        }
        return $parameters['locale'];
    }


    /**
     * Internal insert and get id route by presenter and action.
     *
     * Use in Model::insertAlias().
     *
     * @param $presenter
     * @param $action
     * @return mixed
     */
    private function getIdRouter($presenter, $action)
    {
        $cacheKey = 'getIdRouter-' . $presenter . '-' . $action;
        $id = $this->cache->load($cacheKey);
        if ($id === null) {
            $id = $this->connection->select('id')
                ->from($this->tableRouter)
                ->where('presenter=%s', $presenter)
                ->where('action=%s', $action)
                ->fetchSingle();

            if (!$id) {
                $id = $this->connection->insert($this->tableRouter, [
                    'presenter' => $presenter,
                    'action'    => $action,
                ])->execute(Dibi::IDENTIFIER);
            }

            $this->cache->save($cacheKey, $id, [
                Cache::EXPIRE => '30 minutes',
                Cache::TAGS   => ['router-cache'],
            ]);
        }
        return $id;
    }


    /**
     * Internal insert and get id router alias by id router, id locale, id item and alias.
     *
     * Use in Model::insertAlias().
     *
     * @param $idRouter
     * @param $idLocale
     * @param $idItem
     * @param $alias
     * @return mixed
     */
    private function getIdRouterAlias($idRouter, $idLocale, $idItem, $alias)
    {
        $cacheKey = 'getIdRouterAlias-' . $idRouter . '-' . $idLocale . '-' . $idItem . '-' . $alias;
        $id = $this->cache->load($cacheKey);
        if ($id === null) {
            $cursor = $this->connection->select('id')
                ->from($this->tableRouterAlias)
                ->where('id_router=%i', $idRouter)
                ->where(['id_locale%iN' => $idLocale])
                ->where('alias=%s', $alias);
            if ($idItem) {
                $cursor->where('id_item=%i', $idItem);
            }
            $id = $cursor->fetchSingle();

            $this->cache->save($cacheKey, $id, [
                Cache::EXPIRE => '30 minutes',
                Cache::TAGS   => ['router-cache'],
            ]);
        }

        if (!$id) {
            $id = $this->connection->insert($this->tableRouterAlias, [
                'id_router' => $idRouter,
                'id_locale' => $idLocale,
                'id_item'   => $idItem,
                'alias'     => $alias,
                'added%sql' => 'NOW()',
            ])->execute(Dibi::IDENTIFIER);

            // promaze cache az po vlozeni
            $this->cleanCache();
        }
        return $id;
    }


    /**
     * Internal clean cache.
     */
    private function cleanCache()
    {
        $this->cache->clean([
            Cache::TAGS => ['router-cache'],
        ]);
    }


    /**
     * Manual insert and get id router alias by instnace of presenter and alias string.
     *
     * Use in FilterSlug::__invoke().
     *
     * @param Presenter $presenter
     * @param           $alias
     * @return mixed|null
     */
    public function insertAlias(Presenter $presenter, $alias)
    {
        $result = null;
        $safeAlias = Strings::webalize($alias);
        if ($safeAlias) {
            $idRouter = $this->getIdRouter($presenter->getName(), $presenter->action);
            $result = $this->getIdRouterAlias($idRouter, $this->localeService->getIdByCode($presenter->getParameter('locale')), $presenter->getParameter('id'), $safeAlias);
        }
        return $result;
    }


    /**
     * Create router match.
     *
     * @param       $presenter
     * @param       $action
     * @param       $alias
     * @param array $parameters
     * @return mixed|null
     */
    public function createRouter($presenter, $action, $alias, $parameters = [])
    {
        return $this->insertAlias(new InternalRouterPresetner($presenter, $action, $parameters), $alias);
    }


    /**
     * Delete router match.
     *
     * @param null  $presenter
     * @param null  $action
     * @param null  $alias
     * @param array $parameters
     * @return mixed|null
     */
    public function deleteRouter($presenter = null, $action = null, $alias = null, $parameters = [])
    {
        $result = null;
        if ($presenter && $action && $alias) {
            $result = $this->connection->delete($this->tableRouterAlias)
                ->where('id_router=%i', $this->getIdRouter($presenter, $action))
                ->where('alias=%s', $alias);
            // dodatecne parametry
            if ($parameters) {
                $result->where($parameters);
            }
            $result->execute();
        } elseif ($presenter && $action) {
            // mazani podle presenteru a akce
            $this->connection->delete($this->tableRouter)
                ->where('presenter=%s', $presenter)
                ->where('action=%s', $action)
                ->execute();
        } elseif ($presenter) {
            // mazani podle presenteru
            $this->connection->delete($this->tableRouter)
                ->where('presenter=%s', $presenter)
                ->execute();
        }
        // promazani cache
        $this->cleanCache();
        return $result;
    }


    /**
     * Get router alias for tracy.
     *
     * Use in Panel::getPanel().
     *
     * @param Presenter $presenter
     * @param           $idLocale
     * @param null      $idItem
     * @return mixed
     */
    public function getRouterAlias(Presenter $presenter, $idLocale, $idItem = null)
    {
        $result = $this->connection->select('a.id, a.alias, a.id_item, a.added')
            ->from($this->tableRouter)->as('r')
            ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
            ->where('r.presenter=%s', $presenter->getName())
            ->where(['a.id_locale%iN' => $idLocale])
            ->orderBy('a.added')->desc();

        // add action condition
        if ($presenter->action) {
            $result->where('r.action=%s', $presenter->action);
        }

        // add id condition
        if ($idItem) {
            $result->where('a.id_item=%i', $idItem);
        }
        return $result;
    }
}
