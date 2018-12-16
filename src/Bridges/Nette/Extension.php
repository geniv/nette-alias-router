<?php declare(strict_types=1);

namespace AliasRouter\Bridges\Nette;

use AliasRouter\AliasRouter;
use AliasRouter\Bridges\Tracy\Panel;
use AliasRouter\FilterSlug;
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
        'debugger'    => true,
        'autowired'   => true,
        'driver'      => null,
        'enabled'     => true,
        'domainAlias' => [],
    ];


    /**
     * Load configuration.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // define default
        $builder->addDefinition($this->prefix('default'))
            ->setFactory(AliasRouter::class, [$config['enabled'], $config['domainAlias']])
            ->setAutowired($config['autowired']);

        // define driver
        $driver = $builder->addDefinition($this->prefix('driver'))
            ->setFactory($config['driver'])
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
                ->setFactory(Panel::class, [$driver]);

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
