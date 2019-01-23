<?php
/**
 * Extensional options
 * User: moyo
 * Date: 2019-01-15
 * Time: 19:22
 */

namespace Carno\Serving\Options;

use Carno\Console\Configure;
use Carno\Serving\Extension\Managed;

trait Extensional
{
    /**
     * @param Configure $conf
     */
    protected function optionsEXT(Configure $conf) : void
    {
        Managed::keeper()->options($conf);
    }
}
