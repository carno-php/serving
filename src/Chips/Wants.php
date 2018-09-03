<?php
/**
 * Serving wants
 * User: moyo
 * Date: 2018/5/25
 * Time: 2:44 PM
 */

namespace Carno\Serving\Chips;

use Carno\Console\Boot\Waited;
use Carno\Net\Contracts\Conn;
use Carno\Net\Events;

trait Wants
{
    /**
     * @param Waited $starting
     * @param Waited $stopping
     * @return static
     */
    public function wants(Waited $starting, Waited $stopping) : self
    {
        /**
         * @var Events $events
         */

        $events = $this->events();

        $events
        ->attach(Events\Server::CREATED, static function (Conn $serv) use ($starting, $stopping) {
            $serv->ctx()->set('WG:STARTING', $starting);
            $serv->ctx()->set('WG:STOPPING', $stopping);
        })
        ->attach(Events\Worker::STARTED, static function (Conn $serv) use ($starting) {
            // worker started ... init waiting
            $starting->done()->then(function () use ($serv) {
                logger('serving')->info('Worker is ready', ['id' => $serv->worker(), 'pid' => getmypid()]);
            });
            $starting->perform()->fusion();
        })
        ->attach(Events\Worker::STOPPED, static function (Conn $serv) use ($stopping) {
            // worker stopped ... quit waiting
            $stopping->done()->then(function () use ($serv) {
                logger('serving')->info('Worker is exited', ['id' => $serv->worker(), 'pid' => getmypid()]);
                // some times worker will not exited as expect .. force quit
                $serv->server()->stop();
            });
            $stopping->perform()->fusion();
        });

        return $this;
    }
}
