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
    /** @var array */
    protected $match, $constructUrl;


    /**
     * Driver constructor.
     *
     * @param ILocale $locale
     */
    public function __construct(ILocale $locale)
    {
        $this->locale = $locale;

        $this->match = [];
        $this->constructUrl = [];
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
            $idLocale = $this->locale->getIdByCode($presenter->getParameter('locale', $this->locale->getCodeDefault()));
            $idItem = (int) $presenter->getParameter('id');
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


    /**
     * Get parameters by alias.
     *
     * @param string $locale
     * @param string $alias
     * @return array
     */
    public function getParametersByAlias(string $locale, string $alias): array
    {
        $idLocale = $this->locale->getIdByCode($locale);

        $index = $idLocale . '-' . $alias;

        return (array) ($this->match[$index] ?? []);
    }


    /**
     * Get alias by parameters.
     *
     * @param string $presenter
     * @param array  $parameters
     * @return array
     */
    public function getAliasByParameters(string $presenter, array $parameters): array
    {
        $action = $parameters['action'];
        $idLocale = $this->locale->getIdByCode($parameters['locale']);
        $idItem = $parameters['id'] ?? null;

        $index = $idLocale . '-' . $presenter . '-' . $action . '-' . $idItem;

        return (array) ($this->constructUrl[$index] ?? []);
    }


    /**
     * Get router alias.
     *
     * @param Presenter $presenter
     * @return array
     */
    public function getRouterAlias(Presenter $presenter): array
    {
        $name = $presenter->getName();
        $action = $presenter->action;
        $idLocale = $this->locale->getId();
        $idItem = $presenter->getParameter('id');

        $result = array_filter($this->match, function ($row) use ($name, $action, $idLocale, $idItem) {
            return ($row['presenter'] == $name &&
                $row['action'] == $action &&
                $row['id_locale'] == $idLocale &&
                $row['id_item'] == $idItem);
        });
        return $result;
    }
}
