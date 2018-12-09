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


    /**
     * Save internalData.
     *
     * @param string $presenter
     * @param string $action
     * @param string $alias
     * @param array  $parameters
     * @return int
     */
    abstract protected function saveInternalData(string $presenter, string $action, string $alias, array $parameters = []): int;


    /**
     * Load internal data.
     *
     * @return mixed
     */
    abstract protected function loadInternalData();
}
