<?php
/**
 * Global pool stats monitor
 * User: moyo
 * Date: 2018/9/3
 * Time: 2:10 PM
 */

namespace Carno\Serving\Components\Monitor;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Monitor\Builtin\PoolStatsExporter;
use Carno\Monitor\Daemon;
use Carno\Pool\Contracts\Event;
use Carno\Pool\Observer;
use Carno\Pool\Pool;

class Pooled extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $prerequisites = [Daemon::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        $app->starting()->add(function () {
            Observer::watch(static function (int $ev, Pool $pool) {
                /**
                 * @var PoolStatsExporter[] $exporters
                 */
                static $exporters = [];
                $pid = spl_object_id($pool);
                switch ($ev) {
                    case Event::CREATED:
                        $exporters[$pid] = new PoolStatsExporter($pool);
                        break;
                    case Event::CLOSED:
                        $exporters[$pid]->stop();
                        unset($exporters[$pid]);
                        break;
                }
            });
        });
    }
}
