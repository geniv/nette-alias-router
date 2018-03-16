<?php

namespace AliasRouter\Bridges\Tracy;

use AliasRouter\RouterModel;
use Latte\Engine;
use Locale\ILocale;
use Nette\Application\Application;
use Nette\DI\Container;
use Nette\SmartObject;
use Tracy\Debugger;
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
    /** @var RouterModel */
    private $routerModel;
    /** @var Container */
    private $container;


    /**
     * Panel constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * Register to Tracy.
     *
     * @param RouterModel $routerModel
     */
    public function register(RouterModel $routerModel)
    {
        $this->routerModel = $routerModel;
        Debugger::getBar()->addPanel($this);
    }


    /**
     * Renders HTML code for custom tab.
     *
     * @return string
     */
    public function getTab()
    {
        return '<span title="Alias router">' .
            '<svg height="16" viewBox="0 0 100 100" width="16" xmlns="http://www.w3.org/2000/svg"><title/><path d="M76.92,60H70.35l6.82-38.21L63.74,60H36.26L22.31,19.78,29.65,60H23.08a10.5,10.5,0,1,0,0,21H76.92a10.5,10.5,0,1,0,0-21ZM72.79,38.37,68.3,60H65.84Zm-45.58,0L34.16,60H31.7ZM76.92,79H74V77H72v2H62V77H60v2H51V77H49v2H39V77H37v2H28V77H26v2H23.08a8.5,8.5,0,1,1,0-17H76.92a8.5,8.5,0,1,1,0,17ZM56,70H66V64H56Zm2-4h6v2H58Zm11,4H79V64H69Zm2-4h6v2H71Z"/></svg>' .
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
        $locale = $this->container->getByType(ILocale::class);   // nacteni lokalizacni sluzby
        $application = $this->container->getByType(Application::class);    // nacteni aplikace
        $presenter = $application->getPresenter();

        $params = [
            'routerClass' => get_class($this->routerModel),
            'routes'      => ($presenter ? $this->routerModel->getRouterAlias($presenter, $locale->getId()) : []),
        ];

        $latte = new Engine;
        return $latte->renderToString(__DIR__ . '/PanelTemplate.latte', $params);
    }
}
