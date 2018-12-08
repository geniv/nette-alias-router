<?php declare(strict_types=1);

namespace AliasRouter;


/**
 * Interface IAliasRouter
 *
 * @author  geniv
 * @package AliasRouter
 */
interface IAliasRouter
{

    /**
     * Enable https, default is disable.
     *
     * @param bool $secure
     */
    public function setSecure(bool $secure);


    /**
     * Enable one way router.
     *
     * @param bool $oneWay
     */
    public function setOneWay(bool $oneWay);


    /**
     * Set default parameters, presenter, action and locale.
     *
     * @param string $presenter
     * @param string $action
     * @param string $locale
     */
    public function setDefaultParameters(string $presenter, string $action, string $locale);


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
    public function isEnabled();
}
