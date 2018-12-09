<?php declare(strict_types=1);

namespace AliasRouter\Drivers;


/**
 * Interface IDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
interface IDriver
{

    public function getParametersByAlias(string $locale, string $alias): array;


    public function getAliasByParameters(string $presenter, array $parameters): array;


//    public function cleanCache();


    public function insertAlias(string $presenter, string $string): int;


    public function createRouter(string $presenter, string $action, string $alias, array $parameters = []): int;


    public function deleteRouter(string $presenter = null, string $action = null, string $alias = null, array $parameters = []): int;
}
