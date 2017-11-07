<?php declare(strict_types=1);

namespace AliasRouter;

use Nette\Application\UI\Presenter;


/**
 * Class InternalRouterPresetner
 *
 * @author  geniv
 * @package AliasRouter
 */
class InternalRouterPresetner extends Presenter
{
    private $name;


    /**
     * InternalRouterPresetner constructor.
     *
     * @param       $name
     * @param       $action
     * @param array $parameters
     */
    public function __construct($name, $action, $parameters = [])
    {
        $this->name = $name;
        $this->params = $parameters;
        $this->params['action'] = $action;
    }


    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Get action.
     *
     * @param bool $fullyQualified
     * @return string
     */
    public function getAction($fullyQualified = false): string
    {
        return ($fullyQualified ? ':' . $this->getName() . ':' . $this->params['action'] : $this->params['action']);
    }
}
