<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Locale\ILocale;


/**
 * Class NeonDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
class NeonDriver extends ArrayDriver
{
    /** @var string */
    private $path;


    /**
     * NeonDriver constructor.
     *
     * @param string  $path
     * @param ILocale $locale
     */
    public function __construct(string $path, ILocale $locale)
    {
        $this->path = $path;

        parent::__construct([], $locale);
    }

//TODO sepsat, bude podobne systemu jako: vendor/geniv/nette-translator/src/Drivers/NeonDriver.php


    /**
     * Save internalData.
     *
     * @param string   $presenter
     * @param string   $action
     * @param string   $alias
     * @param int      $idLocale
     * @param int|null $idItem
     * @return int
     */
    protected function saveInternalData(string $presenter, string $action, string $alias, int $idLocale, int $idItem = null): int
    {
        // TODO: Implement saveInternalData() method.
    }


    /**
     * Load internal data.
     */
    protected function loadInternalData()
    {
        // TODO: Implement loadInternalData() method.
    }


    /**
     * Delete router.
     *
     * @param string|null $presenter
     * @param string|null $action
     * @param string|null $alias
     * @param array       $parameters
     * @return int
     */
    public function deleteRouter(string $presenter = null, string $action = null, string $alias = null, array $parameters = []): int
    {
        // TODO: Implement deleteRouter() method.
    }
}
