<?php declare(strict_types=1);

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

    /** @var RouterModel */
    private $routerModel;
    /** @var Application */
    private $application;


    /**
     * FilterSlug constructor.
     *
     * @param RouterModel $routerModel
     * @param Application $application
     */
    public function __construct(RouterModel $routerModel, Application $application)
    {
        $this->routerModel = $routerModel;
        $this->application = $application;
    }


    /**
     * Magic call from template.
     *
     * @param FilterInfo $info
     * @param            $string
     * @throws \Dibi\Exception
     * @throws \Exception
     * @throws \Throwable
     */
    public function __invoke(FilterInfo $info, $string)
    {
        $presenter = $this->application->getPresenter();
        $this->routerModel->insertAlias($presenter, $string);
    }
}
