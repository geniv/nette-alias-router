Alias router
============
Database alias router

Installation
------------
```sh
$ composer require geniv/nette-alias-router
```
or
```json
"geniv/nette-alias-router": ">=1.0.0"
```

require:
```json
"php": ">=7.0.0",
"nette/nette": ">=2.4.0",
"dibi/dibi": ">=3.0.0",
"geniv/nette-locale": ">=1.0.0"
```

Include in application
----------------------
neon configure:
```neon
# alias router
aliasRouter:
#   debugger: false
#   autowired: self     # default self, true|false|self|null
    tablePrefix: %tablePrefix%
#    domainSwitch: true
#    domainAlias:
#        example.cz: cs
#        example.com: en
#        example.de: de
```

neon configure extension:
```neon
extensions:
    aliasRouter: AliasRouter\Bridges\Nette\Extension
```

RouterFactory.php:
```php
public static function createRouter(Locale $locale, AliasRouter $aliasRouter): IRouter
...
$router[] = $aliasRouter;
$aliasRouter->setDefaultParameters('Homepage', 'default', 'cs');
$aliasRouter->setPaginatorVariable('visualPaginator-page');
//$aliasRouter->setSecure(true);
//$aliasRouter->setOneWay(true);
```

usage @layout.latte:
```latte
{if $presenter->context->hasService('aliasRouter.default')}
    {ifset slug}
        {include slug|addSlug}
    {/ifset}
{/if}
```

manual create or delete:
```php
use AliasRouter\Model;
$this->context->getByType(Model::class)->createRouter('Homepage', 'default', 'muj alias');
$this->context->getByType(Model::class)->deleteRouter('Homepage', 'default');
```
