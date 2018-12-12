<?php declare(strict_types=1);

namespace AliasRouter;

use AliasRouter\Drivers\IDriver;
use Nette\Application\IRouter;


/**
 * Interface IAliasRouter
 *
 * @author  geniv
 * @package AliasRouter
 */
interface IAliasRouter
{

    /**
     * Get driver.
     *
     * @return IDriver
     */
    public function getDriver(): IDriver;


    /**
     * Get router.
     *
     * @return IRouter
     */
    public function getRouter(): IRouter;


    /**
     * Get domain alias.
     *
     * @return array
     */
    public function getDomainAlias(): array;


    /**
     * Is secure.
     *
     * @return bool
     */
    public function isSecure(): bool;


    /**
     * Enable https, default is disable.
     *
     * @param bool $secure
     */
    public function setSecure(bool $secure);


    /**
     * Is one way.
     *
     * @return bool
     */
    public function isOneWay(): bool;


    /**
     * Enable one way router.
     *
     * @param bool $oneWay
     */
    public function setOneWay(bool $oneWay);


    /**
     * Get default parameters.
     *
     * @return array
     */
    public function getDefaultParameters(): array;


    /**
     * Set default parameters, presenter, action and locale.
     *
     * @param string $presenter
     * @param string $action
     * @param string $locale
     */
    public function setDefaultParameters(string $presenter, string $action, string $locale);


    /**
     * Get paginator variable.
     *
     * @return string
     */
    public function getPaginatorVariable(): string;


    /**
     * Set paginator variable.
     *
     * @param string $variable
     */
    public function setPaginatorVariable(string $variable);


    /**
     * Is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;
}
