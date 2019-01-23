<?php
/**
 * Serving boots
 * User: moyo
 * Date: 2019-01-19
 * Time: 17:37
 */

namespace Carno\Serving\Chips;

use Carno\Console\Bootstrap;
use Carno\Serving\Extension\Managed;

trait Boots
{
    /**
     * @var Bootstrap
     */
    private $bootstrap = null;

    /**
     * @param Bootstrap $from
     * @return static
     */
    public function bootstrap(Bootstrap $from) : self
    {
        $this->bootstrap = $from;

        $from->loading(...Managed::keeper()->components());

        return $this;
    }
}
