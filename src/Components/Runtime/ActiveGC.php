<?php
/**
 * Active GC in runtime
 * User: moyo
 * Date: 2018/9/3
 * Time: 2:33 PM
 */

namespace Carno\Serving\Components\Runtime;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Monitor\Metrics;
use Carno\Promise\Promise;
use Carno\Promise\Promised;
use Carno\Timer\Timer;

class ActiveGC extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $prerequisites = [Metrics::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        $stopping = Promise::deferred();

        $app->stopping()->add(static function () use ($stopping) {
            $stopping->resolve();
        });

        $app->starting()->add(static function () use ($stopping) {
            // memory limit adjust in worker
            ini_set('memory_limit', env('MEMORY_LIMIT', '512M'));

            // runtime stats monitor
            new class($stopping) {
                /**
                 * @var Metrics\Counter
                 */
                private $gcTimes = null;

                /**
                 * @var Metrics\Counter
                 */
                private $gcPaused = null;

                /**
                 * anonymous constructor.
                 * @param Promised $stopping
                 */
                public function __construct(Promised $stopping)
                {
                    $this->gcTimes = Metrics::counter()->named('php.gc.times');
                    $this->gcPaused = Metrics::counter()->named('php.gc.paused');

                    $gcTimer = Timer::loop(1000, [$this, 'gc']);

                    $stopping->then(function () use ($gcTimer) {
                        Timer::clear($gcTimer);
                    });
                }

                /**
                 */
                public function gc() : void
                {
                    $start = microtime(true);

                    $gc = gc_collect_cycles();

                    if ($gc > 0) {
                        $this->gcTimes->inc();
                        $this->gcPaused->inc((microtime(true) - $start) * 1000);
                    }
                }
            };
        });
    }
}
