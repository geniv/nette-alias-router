<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Locale\ILocale;
use Nette\Application\UI\Presenter;


/**
 * Class ArrayDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
class ArrayDriver extends Driver
{
    /** @var array */
    private $route;


    /**
     * ArrayDriver constructor.
     *
     * @param array   $route
     * @param ILocale $locale
     */
    public function __construct(array $route, ILocale $locale)
    {
        parent::__construct($locale);

        $this->route = $route;
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
        return 0;
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
    protected function saveInternalData(string $presenter, string $action, string $alias, int $idLocale, int $idItem = null): int
    {
        return 0;
    }


    /**
     * Load internal data.
     */
    protected function loadInternalData()
    {
        //
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
        $result = [];
        if (isset($this->route[$locale][$alias])) {
            list($presenter, $action) = explode(':', $this->route[$locale][$alias]);
            $result = [
                'presenter' => $presenter,
                'action'    => $action,
                'id_item'   => null,
            ];
        }
        return $result;
    }


    /**
     * Get alias by parameters.
     *
     * @param string $presenter
     * @param array  $parameters
     * @return string
     */
    public function getAliasByParameters(string $presenter, array $parameters): string
    {
        $result = '';
        $flip = array_flip($this->route[$parameters['locale'] ?? $this->locale->getCodeDefault()]);
        $action = $parameters['action'];
        if (isset($flip[$presenter . ':' . $action])) {
            $result = $flip[$presenter . ':' . $action];
        }
        return $result;
    }


    /**
     * Get router alias.
     *
     * @param Presenter $presenter
     * @return array
     */
    public function getRouterAlias(Presenter $presenter): array
    {
        $locale = $this->locale->getCode();

        $route = $this->route[$locale];
        $presenterName = $presenter->getName();
        $action = $presenter->action;
        array_walk($route, function (&$value, $key) {
            $value = [
                'aid'     => null,
                'name'    => $value,
                'alias'   => $key,
                'added'   => null,
                'id_item' => null,
            ];
        });

        $name = $presenterName . ':' . $action;
        $result = array_filter($route, function ($row) use ($name) {
            return ($row['name'] == $name);
        });
        return $result;
    }
}
