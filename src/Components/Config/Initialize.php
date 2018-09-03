<?php
/**
 * Configuration init
 * User: moyo
 * Date: 2018/9/3
 * Time: 2:56 PM
 */

namespace Carno\Serving\Components\Config;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;

class Initialize extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 80;

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        // reset default assigned
        config()->assigned($app->name());

        // make "global" as upstream of default config
        config()->joining(config('global'));
    }
}
