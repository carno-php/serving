<?php
/**
 * Live reloading via inotify/fsevents
 * User: moyo
 * Date: 09/02/2018
 * Time: 5:58 PM
 */

namespace Carno\Serving\Plugins;

use Carno\Net\Contracts\Conn;
use Carno\Net\Events;
use Carno\Process\Piping;
use Carno\Process\Program;
use Carno\Promise\Promised;
use Carno\Serving\Contracts\Plugins;
use Carno\Timer\Timer;
use Swoole\Async as SWAsync;

class LiveReloading extends Program implements Plugins
{
    /**
     * startup delay of waiter in ms
     */
    private const STARTUP_DELAY = 1500;

    /**
     * @var string
     */
    protected $name = 'worker.reloading';

    /**
     * @var bool
     */
    private $stopping = false;

    /**
     * @var int
     */
    private $master = -1;

    /**
     * @var int
     */
    private $waiter = -1;

    /**
     * @var string
     */
    private $bin = '';

    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return debug();
    }

    /**
     * @param Events $events
     */
    public function hooking(Events $events) : void
    {
        $forked = $this->fork();

        $events
        ->attach(Events\Server::CREATED, static function (Conn $serv) use ($forked) {
            $forked->startup($serv->server()->raw());
        })
        ->attach(Events\Server::STARTUP, static function (Conn $serv) use ($forked) {
            $forked->master($serv->server()->pid());
        })
        ->attach(Events\Server::SHUTDOWN, static function () use ($forked) {
            $forked->shutdown();
        });
    }

    /**
     * @param Piping $piping
     */
    protected function forking(Piping $piping) : void
    {
        $bin = $arg = null;

        $root = defined('CWD') ? CWD : null;

        switch (PHP_OS) {
            case 'Linux':
                $bin = 'inotifywait';
                $arg = sprintf('-r -q -e create -e modify %s', $root);
                break;
            case 'Darwin':
                $bin = 'notifywait';
                $arg = $root;
                break;
        }

        if ($bin && $arg) {
            $exe = exec(sprintf('which %s', $bin));
            $exe && $this->bin = sprintf('%s %s', $exe, $arg);
        }
    }

    /**
     */
    protected function starting() : void
    {
        $this->bin ? $this->waiting() : logger('reloading')->notice('Missing notify bin .. won\'t working');
    }

    /**
     * @param Promised $wait
     */
    protected function stopping(Promised $wait) : void
    {
        $this->stopping = true;

        if ($this->waiter > 0) {
            system(sprintf('kill -TERM %d', $this->waiter));
        }

        $wait->resolve();
    }

    /**
     */
    public function waiting() : void
    {
        $this->waiter = SWAsync::exec($this->bin, function ($stdout, $status) {
            if ($this->master > 0) {
                logger('reloading')->debug('Some files changed .. restart all workers');
                system(sprintf('kill -USR1 %d', $this->master));
            }
            $this->stopping || Timer::after(self::STARTUP_DELAY, [$this, 'waiting']);
        });
    }

    /**
     * @param int $pid
     */
    public function master(int $pid) : void
    {
        $this->master = $pid;
    }
}
