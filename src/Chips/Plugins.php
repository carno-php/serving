<?php
/**
 * Serving plugins
 * User: moyo
 * Date: 2018/5/25
 * Time: 3:21 PM
 */

namespace Carno\Serving\Chips;

use Carno\Serving\Contracts\Plugins as Plugined;

trait Plugins
{
    /**
     * @param Plugined ...$plugins
     * @return static
     */
    public function plugins(Plugined ...$plugins) : self
    {
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
