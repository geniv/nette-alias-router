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
        $builder->addDefinition($this->prefix('filter.slug'))
            ->setFactory(FilterSlug::class)
            ->setAutowired($config['autowired']);

        // define panel
        if (isset($config['debugger']) && $config['debugger']) {
            $builder->addDefinition($this->prefix('panel'))
                ->setFactory(Panel::class)
                ->setAutowired($config['autowired']);
        }
    }


    /**
     * Before Compile.
     */
    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // linked filter to latte
        $builder->getDefinition('latte.latteFactory')
            ->addSetup('addFilter', ['addSlug', $this->prefix('@filter.slug')]);

        if (isset($config['debugger']) && $config['debugger']) {
            // linked panel to tracy
            $builder->getDefinition($this->prefix('model'))
                ->addSetup('?->register(?)', [$this->prefix('@panel'), '@self']);
        }
    }
}
