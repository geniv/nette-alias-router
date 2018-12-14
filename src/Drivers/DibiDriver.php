<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use dibi;
use Dibi\Connection;
use Dibi\UniqueConstraintViolationException;
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
    /** @var array */
    private $match, $constructUrl;


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

        $this->loadInternalData();
    }


    /**
     * Delete router.
     *
     * @param string|null $presenter
     * @param string|null $action
     * @param string|null $alias
     * @param array       $parameters
     * @return int
     * @throws \Dibi\Exception
     */
    public function deleteRouter(string $presenter = null, string $action = null, string $alias = null, array $parameters = []): int
    {
        $result = 0;
        if ($presenter && $action && $alias) {
            $result = $this->connection->delete($this->tableRouterAlias)
                ->where([
                    'id_router' => $this->getIdRouter($presenter, $action),
                    'alias'     => $alias,
                ]);
            // dodatecne parametry
            if ($parameters) {
                $result->where($parameters);
            }
            $result->execute();
        } elseif ($presenter && $action) {
            // mazani podle presenteru a akce
            $this->connection->delete($this->tableRouter)
                ->where([
                    'presenter' => $presenter,
                    'action'    => $action,
                ])->execute();
        } elseif ($presenter) {
            // mazani podle presenteru
            $this->connection->delete($this->tableRouter)
                ->where(['presenter' => $presenter])
                ->execute();
        }
        // promazani cache
        $this->cleanCache();
        return $result;
    }


    /**
     * Clean cache.
     */
    public function cleanCache()
    {
        // internal clean cache
        $this->cache->clean([
            Cache::TAGS => ['loadData'],
        ]);
    }


    /**
     * Get idRouter.
     *
     * @param string $presenter
     * @param string $action
     * @return int
     */
    private function getIdRouter(string $presenter, string $action): int
    {
        $result = $this->connection->select('id')
            ->from($this->tableRouter)
            ->where([
                'presenter' => $presenter,
                'action'    => $action,
            ])
            ->fetchSingle();    //FIXME cachovat?!
        return $result;
    }


    /**
     * Save internalData.
     *
     * @param string   $presenter
     * @param string   $action
     * @param string   $alias
     * @param int      $idLocale
     * @param int|null $idItem
     * @return int
     * @throws \Dibi\Exception
     */
    protected function saveInternalData(string $presenter, string $action, string $alias, int $idLocale, int $idItem = null): int
    {
        $idItem = $parameters['id_item'] ?? null;

        $idRouter = $this->getIdRouter($presenter, $action);
        if (!$idRouter) {
            $idRouter = $this->connection->insert($this->tableRouter, [
                'presenter' => $presenter,
                'action'    => $action,
            ])->execute(Dibi::IDENTIFIER);
        }

        $cursor = $this->connection->select('id')
            ->from($this->tableRouterAlias)
            ->where([
                'id_router' => $idRouter,
                'id_locale' => $idLocale,
                'alias'     => $alias,
            ]);
        if ($idItem) {
            $cursor->where(['id_item' => $idItem]);
        }
        $id = $cursor->fetchSingle();   //FIXME cachovat!!

        if (!$id) {
            try {
                $id = $this->connection->insert($this->tableRouterAlias, [
                    'id_locale' => $idLocale,
                    'id_router' => $idRouter,
                    'id_item'   => $idItem,
                    'alias'     => $alias,
                    'added%sql' => 'NOW()',
                ])->execute(Dibi::IDENTIFIER);
            } catch (UniqueConstraintViolationException $e) {
                dump($e);   //TODO doresit stejne linky
//                // recursive resolve duplicate alias
//                $al = explode('--', $alias);    // explode alias
//                if (count($al) > 1) {
//                    $alias = implode(array_slice($al, 0, -1)) . '--' . ($al[count($al) - 1] + 1);   // implode alias
//                } else {
//                    $alias .= '--' . 1; // first repair name
//                }
//                $id = $this->getIdRouterAlias($idRouter, $idLocale, $idItem, $alias);   // but db autoincrement is still increment :(
            }
        }

//        $this->cleanCache();

        return $id;
    }


    /**
     * Load internal data.
     */
    protected function loadInternalData()
    {
        $this->match = $this->connection->select('r.id rid, a.id aid, r.presenter, r.action, a.id_item, CONCAT(a.id_locale, "-", a.alias) uid')
            ->from($this->tableRouter)->as('r')
            ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
            ->fetchAssoc('uid');    //FIXME cachovat!!

        $this->constructUrl = $this->connection->select('r.id rid, a.id aid, a.alias, a.id_item, CONCAT(a.id_locale, "-", r.presenter, "-", IFNULL(r.action,"-"), IFNULL(a.id_item,"-")) uid')
            ->from($this->tableRouter)->as('r')
            ->join($this->tableRouterAlias)->as('a')->on('a.id_router=r.id')
            ->orderBy(['r.id', 'a.id_locale'])->asc()
            ->orderBy('a.added')->desc()
            ->fetchAssoc('uid');    //FIXME cachovat!!
    }


    /**
     * Get parameters by alias.
     *
     * @param string $locale
     * @param string $alias
     * @return array
     */
    public function getParametersByAlias(string $locale, string $alias): array
    {
        $idLocale = $this->locale->getIdByCode($locale);

        $index = $idLocale . '-' . $alias;

        return (array) ($this->match[$index] ?? []);
    }


    /**
     * Get alias by parameters.
     *
     * @param string $presenter
     * @param array  $parameters
     * @return array
     */
    public function getAliasByParameters(string $presenter, array $parameters): array
    {
        $action = $parameters['action'];
        $idLocale = $this->locale->getIdByCode($parameters['locale']);
        $idItem = $parameters['id_item'] ?? null;

        $index = $idLocale . '-' . $presenter . '-' . $action . '-' . $idItem;

        return (array) ($this->constructUrl[$index] ?? []);
    }
}
