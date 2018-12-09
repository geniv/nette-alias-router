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

    public function getParametersByAlias(string $locale, string $alias);


    public function getAliasByParameters(string $presenter, array $parameters);


//    public function cleanCache();


    public function insertAlias($presenter, $string);


    public function createRouter($presenter, $action, $alias, $parameters = []);


    public function deleteRouter($presenter = null, $action = null, $alias = null, $parameters = []);
}
