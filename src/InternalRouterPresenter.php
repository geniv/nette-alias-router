<?php

namespace AliasRouter;

use Nette\Application\UI\Presenter;


/**
 * Class InternalRouterPresenter
 *
 * @author  geniv
 * @package AliasRouter
 */
class InternalRouterPresenter extends Presenter
{
    private $name;


    /**
     * InternalRouterPresenter constructor.
     *
     * @param       $name
     * @param       $action
     * @param array $parameters
     */
    public function __construct($name, $action, array $parameters = [])
    {
        $this->name = $name;
        $this->params = $parameters;    // define in Nette\Application\UI\Component
        $this->params['action'] = $action;
    }


    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get action.
     *
     * @param bool $fullyQualified
     * @return mixed|string
     */
    public function getAction($fullyQualified = false)
    {
        return ($fullyQualified ? ':' . $this->getName() . ':' . $this->params['action'] : $this->params['action']);
    }
}
