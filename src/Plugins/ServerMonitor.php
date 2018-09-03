<?php
/**
 * Server monitor [swoole]
 * User: moyo
 * Date: 2018/6/15
 * Time: 10:00 AM
 */

namespace Carno\Serving\Plugins;

use Carno\Monitor\Contracts\Labeled;
use Carno\Monitor\Metrics;
use Carno\Monitor\Ticker;
use Carno\Net\Contracts\Conn;
use Carno\Net\Events;
use Carno\Serving\Contracts\Plugins;
use Swoole\Server as SWServer;

class ServerMonitor implements Plugins
{
    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return extension_loaded('swoole') && class_exists(Metrics::class);
    }

    /**
     * @param Events $events
     */
    public function hooking(Events $events) : void
    {
        $events->attach(Events\Worker::STARTED, function (Conn $serv) {
            if (($sw = $serv->server()->raw()) && $sw instanceof SWServer) {
                Ticker::new([
                    Metrics::gauge()->named('server.connections')->labels([Labeled::GLOBAL => true]),
                    Metrics::gauge()->named('server.accepts')->labels([Labeled::GLOBAL => true]),
                ], static function (Metrics\Gauge $sc, Metrics\Gauge $sa) use ($sw) {
                    $current = $sw->stats();
                    $sc->set($current['connection_num'] ?? 0);
                    $sa->set($current['accept_count'] ?? 0);
                });
            }
        });
    }
}
