<?php
/**
 * Serving plugin API
 * User: moyo
 * Date: 2018/5/25
 * Time: 3:22 PM
 */

namespace Carno\Serving\Contracts;

use Carno\Net\Events;

interface Plugins
{
    /**
     * @return bool
     */
    public function enabled() : bool;

    /**
     * @param Events $events
     */
    public function hooking(Events $events) : void;
}
