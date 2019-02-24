<?php declare(strict_types=1);

namespace AliasRouter;

use AliasRouter\Drivers\IDriver;
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

    /** @var IDriver */
    private $driver;
    /** @var Application */
    private $application;


    /**
     * FilterSlug constructor.
     *
     * @param IDriver     $driver
     * @param Application $application
     */
    public function __construct(IDriver $driver, Application $application)
    {
        $this->driver = $driver;
        $this->application = $application;
    }


    /**
     * Magic call from template.
     *
     * @param FilterInfo $info
     * @param            $string
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    public function __invoke(FilterInfo $info, $string)
    {
        $presenter = $this->application->getPresenter();
        $this->driver->insertAlias($presenter, $string);
        return '';
    }
}
