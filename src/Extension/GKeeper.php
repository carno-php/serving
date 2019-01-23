<?php
/**
 * Global keeper
 * User: moyo
 * Date: 2019-01-15
 * Time: 19:39
 */

namespace Carno\Serving\Extension;

use Carno\Container\DI;

trait GKeeper
{
    /**
     * @return Managed
     */
    public static function keeper() : Managed
    {
        if (DI::has(Managed::class)) {
            return DI::get(Managed::class);
        }

        /**
         * @var Managed $mgr
         */

        DI::set(Managed::class, $mgr = DI::object(Managed::class));

        foreach (get_defined_constants(true)['user'] ?? [] as $name => $value) {
            if (substr($name, 5, 16) === '_SERV_EXTENSION_') {
                $mgr->load(new $value);
            }
        }

        return $mgr;
    }
}
