<?php
/**
 * Classifier preparing
 * User: moyo
 * Date: 2018/11/21
 * Time: 5:03 PM
 */

namespace Carno\Serving\Components\Discovery;

use Carno\Cluster\Classify\Classified;
use Carno\Cluster\Classify\Selector;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;

class Classify extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 40;

    /**
     * @var array
     */
    protected $prerequisites = [Classified::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        DI::set(Classified::class, new Selector());
    }
}
