<?php

namespace AliasRouter;

use Latte\Runtime\FilterInfo;
use Nette\Application\Application;
use Nette\SmartObject;


/**
 * Class FilterSlug
 *
 * @author  geniv
 * @package AliasRouter
 */
class FilterSlug
{
    use SmartObject;

    /** @var Model router model */
    private $model;
    /** @var Application current application */
    private $application;


    /**
     * FilterSlug constructor.
     *
     * @param Model       $model
     * @param Application $application
     */
    public function __construct(Model $model, Application $application)
    {
        $this->model = $model;
        $this->application = $application;
    }


    /**
     * Magic call from template.
     *
     * @param FilterInfo $info
     * @param            $string
     */
    public function __invoke(FilterInfo $info, $string)
    {
        $presenter = $this->application->getPresenter();
        $this->model->insertAlias($presenter, $string);
    }
}
