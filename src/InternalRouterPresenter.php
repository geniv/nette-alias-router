<?php declare(strict_types=1);

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
    /** @var string */
    private $name;


    /**
     * InternalRouterPresenter constructor.
     *
     * @param string $name
     * @param string $action
     * @param array  $parameters
     */
    public function __construct($name = '', $action = '', array $parameters = [])
    {
        parent::__construct();

        $this->name = $name;
        $this->params = $parameters;    // define in Nette\Application\UI\Component
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
