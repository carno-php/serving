<?php
/**
 * Extensions API
 * User: moyo
 * Date: 2019-01-15
 * Time: 18:46
 */

namespace Carno\Serving\Contracts;

use Carno\Console\Bootstrap;
use Carno\Console\Configure;
use Carno\Serving\Extension\Components;
use Carno\Serving\Extension\Plugins;

interface Extensions
{
    /**
     * @param Configure $conf
     */
    public function options(Configure $conf) : void;

    /**
     * @param Bootstrap $boot
     * @return Plugins
     */
    public function plugins(Bootstrap $boot) : Plugins;

    /**
     * @return Components
     */
    public function components() : Components;
}
