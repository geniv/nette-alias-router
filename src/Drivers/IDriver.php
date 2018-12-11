<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Nette\Application\UI\Presenter;


/**
 * Interface IDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
interface IDriver
{

    /**
     * Get parameters by alias.
     *
     * @param string $locale
     * @param string $alias
     * @return array
     */
    public function getParametersByAlias(string $locale, string $alias): array;


    /**
     * Get alias by parameters.
     *
     * @param string $presenter
     * @param array  $parameters
     * @return array
     */
    public function getAliasByParameters(string $presenter, array $parameters): array;


    /**
     * Insert alias.
     *
     * @param Presenter $presenter
     * @param string    $alias
     * @return int
     */
    public function insertAlias(Presenter $presenter, string $alias): int;


    /**
     * Create router.
     *
     * @param string $presenter
     * @param string $action
     * @param string $alias
     * @param array  $parameters
     * @return int
     */
    public function createRouter(string $presenter, string $action, string $alias, array $parameters = []): int;


    /**
     * Delete router.
     *
     * @param string|null $presenter
     * @param string|null $action
     * @param string|null $alias
     * @param array       $parameters
     * @return int
     */
    public function deleteRouter(string $presenter = null, string $action = null, string $alias = null, array $parameters = []): int;


    /**
     * Clean cache.
     */
    public function cleanCache();
}
