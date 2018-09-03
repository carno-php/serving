<?php
/**
 * Global runtime monitor
 * User: moyo
 * Date: 2018/9/3
 * Time: 2:15 PM
 */

namespace Carno\Serving\Components\Monitor;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Coroutine\Stats as COStats;
use Carno\Monitor\Builtin\ProgramStatus;
use Carno\Monitor\Daemon;
use Carno\Monitor\Metrics;
use Carno\Monitor\Ticker;
use Carno\Promise\Stacked as POStacked;
use Carno\Promise\Stats as POStats;

class Runtime extends Component implements Bootable
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
            // default monitor of program status like memory and etc
            new ProgramStatus;

            // monitor of coroutine & promises
            Ticker::new([
                Metrics::gauge()->named('coroutine.running'),
                Metrics::gauge()->named('promise.pending'),
                Metrics::gauge()->named('promise.stacked'),
            ], static function (Metrics\Gauge $co, Metrics\Gauge $po, Metrics\Gauge $stack) {
                $co->set(COStats::running());
                $po->set(POStats::pending());
                $stack->set(POStacked::size());
            });
        });
    }
}
