<?php

namespace AliasRouter\Bridges\Nette;

use AliasRouter\Bridges\Tracy\Panel;
use AliasRouter\FilterSlug;
use AliasRouter\Model;
use Nette\DI\CompilerExtension;


/**
 * Class Extension
 *
 * nette extension pro alias router jako rozsireni
 *
 * @author  geniv
 * @package AliasRouter\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array vychozi hodnoty */
    private $defaults = [
        'table'        => null,
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

        // definice modelu
        $builder->addDefinition($this->prefix('default'))
            ->setClass(Model::class, [$config]);

        // definice filteru
        $builder->addDefinition($this->prefix('filter.slug'))
            ->setClass(FilterSlug::class);

        // definice panelu
        $builder->addDefinition($this->prefix('panel'))
            ->setClass(Panel::class);
    }


    /**
     * Before Compile.
     */
    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();

        // pripojeni filru do latte
        $builder->getDefinition('latte.latteFactory')
            ->addSetup('addFilter', ['addSlug', $this->prefix('@filter.slug')]);

        // pripojeni panelu do tracy
        $builder->getDefinition($this->prefix('default'))
            ->addSetup('?->register(?)', [$this->prefix('@panel'), '@self']);
    }
}
