<?php

namespace AliasRouter\Bridges\Nette;

use AliasRouter\AliasRouter;
use AliasRouter\Bridges\Tracy\Panel;
use AliasRouter\FilterSlug;
use AliasRouter\RouterModel;
use Nette\DI\CompilerExtension;


/**
 * Class Extension
 *
 * @author  geniv
 * @package AliasRouter\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array default values */
    private $defaults = [
        'debugger'     => true,
        'autowired'    => 'self',
        'tablePrefix'  => null,
        'enabled'      => true,
        'domainSwitch' => false,
        'domainAlias'  => [],
    ];


    /**
     * Load configuration.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // define router
        $builder->addDefinition($this->prefix('default'))
            ->setFactory(AliasRouter::class)
            ->setAutowired($config['autowired']);

        // define model
        $builder->addDefinition($this->prefix('model'))
            ->setFactory(RouterModel::class, [$config])
            ->setAutowired($config['autowired']);

        // define filter
        $slug = $builder->addDefinition($this->prefix('filter.slug'))
            ->setFactory(FilterSlug::class)
            ->setAutowired($config['autowired']);

        // linked filter to latte
        $builder->getDefinition('latte.latteFactory')
            ->addSetup('addFilter', ['addSlug', $slug]);

        // define panel
        if ($config['debugger']) {
            $panel = $builder->addDefinition($this->prefix('panel'))
                ->setFactory(Panel::class);

            // linked panel to tracy
            $builder->getDefinition('tracy.bar')
                ->addSetup('addPanel', [$panel]);
        }
    }


    /**
     * Before Compile.
     */
    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        if ($config['debugger']) {
            $onRequest = 'application.application';
            // linked to application request
            $builder->getDefinition($onRequest)
                ->addSetup('$service->onRequest[] = ?', [[$this->prefix('@panel'), 'onRequest']]);
        }
    }
}
