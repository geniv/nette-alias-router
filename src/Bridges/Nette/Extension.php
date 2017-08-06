<?php

namespace AliasRouter\Bridges\Nette;

use AliasRouter\Bridges\Tracy\Panel;
use AliasRouter\FilterSlug;
use AliasRouter\Model;
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

        // define model
        $builder->addDefinition($this->prefix('default'))
            ->setClass(Model::class, [$config]);

        // define filter
        $builder->addDefinition($this->prefix('filter.slug'))
            ->setClass(FilterSlug::class);

        // define panel
        if (isset($config['debugger']) && $config['debugger']) {
            $builder->addDefinition($this->prefix('panel'))
                ->setClass(Panel::class);
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
            $builder->getDefinition($this->prefix('default'))
                ->addSetup('?->register(?)', [$this->prefix('@panel'), '@self']);
        }
    }
}
