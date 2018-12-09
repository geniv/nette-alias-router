<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Locale\ILocale;
use Nette\SmartObject;


/**
 * Class Driver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
abstract class Driver implements IDriver
{
    use SmartObject;

    /** @var ILocale */
    protected $locale;


    /**
     * Driver constructor.
     *
     * @param ILocale $locale
     */
    public function __construct(ILocale $locale)
    {
        $this->locale = $locale;
    }


    //TODO abstraktni metodu alespon jednu!


    abstract protected function saveInternalData(string $presenter, string $action, string $alias, array $parameters = []): int;


    abstract protected function loadInternalData();
}
