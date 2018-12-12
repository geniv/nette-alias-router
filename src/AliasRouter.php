<?php declare(strict_types=1);

namespace AliasRouter;

use AliasRouter\Drivers\IDriver;
use Nette\Application\IRouter;
use Nette\SmartObject;


/**
 * Class AliasRouter
 *
 * @author  geniv
 * @package AliasRouter
 */
class AliasRouter implements IAliasRouter
{
    use SmartObject;

    /** @var array */
    private $domainAlias = [];
    /** @var bool */
    private $enabled = true;
    /** @var IDriver */
    private $driver;

    /** @var bool */
    private $secure = false;
    /** @var bool */
    private $oneWay = false;

    /** @var array */
    private $defaultParameters = [];
    /** @var string */
    private $paginatorVariable = 'vp';


    /**
     * AliasRouter constructor.
     *
     * @param bool    $enabled
     * @param array   $domainAlias
     * @param IDriver $driver
     */
    public function __construct(bool $enabled, array $domainAlias, IDriver $driver)
    {
        $this->domainAlias = $domainAlias;
        $this->enabled = $enabled;
        $this->driver = $driver;
    }


    /**
     * Get driver.
     *
     * @return IDriver
     */
    public function getDriver(): IDriver
    {
        return $this->driver;
    }


    /**
     * Get router.
     *
     * @return IRouter
     */
    public function getRouter(): IRouter
    {
        return new Router($this);
    }


    /**
     * Get domain alias.
     *
     * @return array
     */
    public function getDomainAlias(): array
    {
        return $this->domainAlias;
    }


    /**
     * Is secure.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }


    /**
     * Enable https, default is disable.
     *
     * @param bool $secure
     */
    public function setSecure(bool $secure)
    {
        $this->secure = $secure;
    }


    /**
     * Is one way.
     *
     * @return bool
     */
    public function isOneWay(): bool
    {
        return $this->oneWay;
    }


    /**
     * Enable one way router.
     *
     * @param bool $oneWay
     */
    public function setOneWay(bool $oneWay)
    {
        $this->oneWay = $oneWay;
    }


    /**
     * Get default parameters.
     *
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return $this->defaultParameters;
    }


    /**
     * Set default parameters, presenter, action and locale.
     *
     * @param string $presenter
     * @param string $action
     * @param string $locale
     */
    public function setDefaultParameters(string $presenter, string $action, string $locale)
    {
        $this->defaultParameters = [
            'presenter' => $presenter,
            'action'    => $action,
            'locale'    => $locale,
        ];
    }


    /**
     * Get paginator variable.
     *
     * @return string
     */
    public function getPaginatorVariable(): string
    {
        return $this->paginatorVariable;
    }


    /**
     * Set paginator variable.
     *
     * @param string $variable
     */
    public function setPaginatorVariable(string $variable)
    {
        $this->paginatorVariable = $variable;
    }


    /**
     * Is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
