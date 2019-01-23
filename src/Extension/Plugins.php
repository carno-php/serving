<?php
/**
 * Extensional plugins
 * User: moyo
 * Date: 2019-01-15
 * Time: 19:04
 */

namespace Carno\Serving\Extension;

use Carno\Serving\Contracts\Plugins as Plugined;

final class Plugins
{
    /**
     * @var Plugined[]
     */
    private $plugins = [];

    /**
     * Plugins constructor.
     * @param Plugined ...$plugins
     */
    public function __construct(Plugined ...$plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return Plugined[]
     */
    public function list() : array
    {
        return $this->plugins;
    }
}
