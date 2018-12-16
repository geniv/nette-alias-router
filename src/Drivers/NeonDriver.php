<?php declare(strict_types=1);

namespace AliasRouter\Drivers;

use Locale\ILocale;
use Nette\Neon\Neon;


/**
 * Class NeonDriver
 *
 * @author  geniv
 * @package AliasRouter\Drivers
 */
class NeonDriver extends ArrayDriver
{

    /**
     * NeonDriver constructor.
     *
     * @param string  $path
     * @param ILocale $locale
     */
    public function __construct(string $path, ILocale $locale)
    {
        $data = [];
        if ($path && file_exists($path)) {
            $data = Neon::decode(file_get_contents($path));
        }
        parent::__construct($data, $locale);
    }
}
