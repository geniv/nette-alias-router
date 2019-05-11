<?php declare(strict_types=1);

namespace AliasRouter;

use AliasRouter\Drivers\IDriver;
use Exception;
use Latte\Runtime\FilterInfo;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;
use Throwable;


/**
 * Class FilterSlug
 *
 * @author  geniv
 * @package AliasRouter
 */
class FilterSlug
{
    use SmartObject;

    /** @var IDriver */
    private $driver;
    /** @var Application */
    private $application;


    /**
     * FilterSlug constructor.
     *
     * @param Application  $application
     * @param IDriver|null $driver
     */
    public function __construct(Application $application, IDriver $driver = null)
    {
        $this->application = $application;
        $this->driver = $driver;
    }


    /**
     * Magic call from template.
     *
     * @param FilterInfo $info
     * @param            $string
     * @return string
     * @throws Exception
     * @throws Throwable
     */
    public function __invoke(FilterInfo $info, $string)
    {
        /** @var Presenter $presenter */
        $presenter = $this->application->getPresenter();
        $this->driver->insertAlias($presenter, $string);
        return '';
    }
}
