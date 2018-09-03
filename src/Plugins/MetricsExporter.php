<?php
/**
 * Metrics exporter inject
 * User: moyo
 * Date: 2018/5/25
 * Time: 11:04 PM
 */

namespace Carno\Serving\Plugins;

use Carno\Container\DI;
use Carno\Monitor\Daemon;
use Carno\Net\Contracts\Conn;
use Carno\Net\Events;
use Carno\Serving\Contracts\Plugins;

class MetricsExporter implements Plugins
{
    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return DI::has(Daemon::class);
    }

    /**
     * @param Events $events
     */
    public function hooking(Events $events) : void
    {
        /**
         * @var Daemon $forked
         */

        $forked = DI::get(Daemon::class);

        $events
        ->attach(Events\Server::CREATED, static function (Conn $serv) use ($forked) {
            $forked->startup($serv->server()->raw());
        })
        ->attach(Events\Server::SHUTDOWN, static function () use ($forked) {
            $forked->shutdown();
        });
    }
}
