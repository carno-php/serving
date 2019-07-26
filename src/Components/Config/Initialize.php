<?php
/**
 * Configuration init
 * User: moyo
 * Date: 2018/9/3
 * Time: 2:56 PM
 */

namespace Carno\Serving\Components\Config;

use function Carno\Config\conf;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Serving\Contracts\ScopedConf;

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
        conf()->assigned($app->name());

        // make "global" as upstream of default config
        conf()->joining(conf(ScopedConf::COM));
    }
}
