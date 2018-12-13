<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use AliasRouter\InternalRouterPresenter;
use Locale\ILocale;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;
use Nette\Utils\Strings;


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
     * @param string   $presenter
     * @param string   $action
     * @param string   $alias
     * @param int      $idLocale
     * @param int|null $idItem
     * @return int
     */
    abstract protected function saveInternalData(string $presenter, string $action, string $alias, int $idLocale, int $idItem = null): int;


    /**
     * Load internal data.
     *
     * @return mixed
     */
    abstract protected function loadInternalData();


    /**
     * Insert alias.
     *
     * @param Presenter $presenter
     * @param string    $alias
     * @return int
     */
    public function insertAlias(Presenter $presenter, string $alias): int
    {
        $result = 0;
        $safeAlias = Strings::webalize($alias, '/');    // webalize with ignore /
        if ($safeAlias) {
            $idLocale = $this->locale->getIdByCode($presenter->getParameter('locale'));
            $idItem = $presenter->getParameter('id');
            $result = $this->saveInternalData($presenter->getName(), $presenter->action, $safeAlias, $idLocale, $idItem);
        }
        return $result;
    }


    /**
     * Create router.
     *
     * @param string $presenter
     * @param string $action
     * @param string $alias
     * @param array  $parameters
     * @return int
     */
    public function createRouter(string $presenter, string $action, string $alias, array $parameters = []): int
    {
        return $this->insertAlias(new InternalRouterPresenter($presenter, $action, $parameters), $alias);
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


    /**
     * Get code locale.
     *
     * @param array $parameters
     * @param array $domainAlias
     * @return string
     */
    public function getCodeLocale(array $parameters, array $domainAlias): string
    {
        // null locale => empty locale in url
        if (!isset($parameters['locale'])) {
            return '';
        }

        // nullable locale in main locale or domain switch
        if (isset($parameters['locale']) && $parameters['locale'] == $this->locale->getCodeDefault() || $domainAlias) {
            return '';
        }
        return $parameters['locale'];
    }
}
