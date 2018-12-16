<?php declare(strict_types=1);

namespace AliasRouter\Bridges\Tracy;

use AliasRouter\Drivers\IDriver;
use Latte\Engine;
use Locale\ILocale;
use Nette\Application\Application;
use Nette\Application\Request;
use Nette\SmartObject;
use Tracy\IBarPanel;


/**
 * Class Panel
 *
 * @author  geniv
 * @package AliasRouter\Bridges\Tracy
 */
class Panel implements IBarPanel
{
    use SmartObject;
    /** @var IDriver */
    private $driver;
    /** @var Application */
    private $application;
    /** @var ILocale */
    private $locale;


    /**
     * Panel constructor.
     *
     * @param IDriver $driver
     * @param ILocale $locale
     */
    public function __construct(IDriver $driver, ILocale $locale)
    {
        $this->driver = $driver;
        $this->locale = $locale;
    }


    /**
     * On request.
     *
     * @param Application $application
     * @param Request     $request
     */
    public function onRequest(Application $application, Request $request)
    {
        $this->application = $application;
    }


    /**
     * Renders HTML code for custom tab.
     *
     * @return string
     */
    public function getTab()
    {
        return '<span title="Alias router">' .
            '<svg height="16" viewBox="0 0 100 100" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M76.92,60H70.35l6.82-38.21L63.74,60H36.26L22.31,19.78,29.65,60H23.08a10.5,10.5,0,1,0,0,21H76.92a10.5,10.5,0,1,0,0-21ZM72.79,38.37,68.3,60H65.84Zm-45.58,0L34.16,60H31.7ZM76.92,79H74V77H72v2H62V77H60v2H51V77H49v2H39V77H37v2H28V77H26v2H23.08a8.5,8.5,0,1,1,0-17H76.92a8.5,8.5,0,1,1,0,17ZM56,70H66V64H56Zm2-4h6v2H58Zm11,4H79V64H69Zm2-4h6v2H71Z"/></svg>' .
            'Alias router' .
            '</span>';
    }


    /**
     * Renders HTML code for custom panel.
     *
     * @return string
     */
    public function getPanel()
    {
        $presenter = $this->application->getPresenter();
        $routes = $this->driver->getRouterAlias($presenter, $this->locale->getId(), $presenter->getParameter('id'));
        $params = [
            'routes' => ($presenter ? $routes : []),
        ];
        $latte = new Engine;
        return $latte->renderToString(__DIR__ . '/PanelTemplate.latte', $params);
    }
}
