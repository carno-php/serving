<?php
/**
 * Serving plugins
 * User: moyo
 * Date: 2018/5/25
 * Time: 3:21 PM
 */

namespace Carno\Serving\Chips;

use Carno\Serving\Contracts\Plugins as Plugined;
use Carno\Serving\Extension\Managed;

trait Plugins
{
    /**
     * @param Plugined ...$plugins
     * @return static
     */
    public function plugins(Plugined ...$plugins) : self
    {
        if ($this->bootstrap ?? null) {
            array_push($plugins, ...Managed::keeper()->plugins($this->bootstrap));
        }

        foreach ($plugins as $plugin) {
            if ($plugin->enabled()) {
                $plugin->hooking($this->events());
            } else {
                logger('serving')->info('Plugin not enabled', ['com' => get_class($plugin)]);
            }
        }

        return $this;
    }
}
