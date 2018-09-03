<?php
/**
 * Global monitor daemon init
 * User: moyo
 * Date: 2018/9/3
 * Time: 2:16 PM
 */

namespace Carno\Serving\Components\Monitor;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;
use Carno\Monitor\Daemon;
use Carno\Monitor\Harvester;
use Carno\Monitor\Ticker;
use Carno\Net\Address;
use Carno\Serving\Contracts\Options;

class Daemons extends Component implements Bootable
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
        $args = $app->input();

        // create default daemon
        if ($args->hasOption(Options::METRICS_PORT) && $port = $args->getOption(Options::METRICS_PORT)) {
            (new Daemon(
                $app->name(),
                new Address(sprintf(':%d', $port)),
                new Address($args->hasOption(Options::METRICS_GATE) ? $args->getOption(Options::METRICS_GATE) : '')
            ))->fork();
        }

        $app->starting()->add(static function () {
            /**
             * @var Daemon $dm
             */
            // check and start default daemon
            (($dm = DI::has(Daemon::class) ? DI::get(Daemon::class) : null) && !$dm->started()) && $dm->startup();
        });

        $app->stopping()->add(static function () {
            Harvester::shutdown();
            Ticker::exit();
        });
    }
}
