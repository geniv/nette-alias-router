Alias router
============

Installation
------------
```sh
$ composer require geniv/nette-alias-router
```
or
```json
"geniv/nette-alias-router": "^3.0"
```

require:
```json
"php": "^7.1",
"nette/nette": "^2.4",
"dibi/dibi": "^4.0",
"geniv/nette-locale": "^2.0"
```

Include in application
----------------------

available source drivers:
- ArrayDriver ()
- NeonDriver ()
- DibiDriver (dibi + cache `_AliasRouter-DibiDriver`)

In router alias is not good idea change last alias, but insert new alias with new datetime stamp.

neon configure:
```neon
# alias router
aliasRouter:
#   debugger: true
#   autowired: true
#    driver: AliasRouter\Drivers\ArrayDriver()
#    driver: AliasRouter\Drivers\NeonDriver()
    driver: AliasRouter\Drivers\DibiDriver(%tablePrefix%)
#   enabled: true
#   domainAlias:
#       example.cz: cs
#       example.com: en
#       example.de: de
```

neon configure extension:
```neon
extensions:
    aliasRouter: AliasRouter\Bridges\Nette\Extension
```

Available interface: `IAliasRouter`

RouterFactory.php:
```php
public static function createRouter(ILocale $locale, IAliasRouter $aliasRouter): IRouter
...
if ($aliasRouter->isEnabled()) {
    $aliasRouter->setDefaultParameters('Homepage', 'default', 'cs');
    $aliasRouter->setPaginatorVariable('visualPaginator-page');
    //$aliasRouter->setSecure(true);
    //$aliasRouter->setOneWay(true);
    $router[] = $aliasRouter->getRouter();
}
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
use AliasRouter\RouterModel;
$this->context->getByType(RouterModel::class)->createRouter('Homepage', 'default', 'muj alias');
$this->context->getByType(RouterModel::class)->deleteRouter('Homepage', 'default');
```
