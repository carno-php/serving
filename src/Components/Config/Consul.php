<?php
/**
 * Configure via consul
 * User: moyo
 * Date: 13/12/2017
 * Time: 10:17 AM
 */

namespace Carno\Serving\Components\Config;

use Carno\Config\Loaders\Consul as ConsulLD;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Consul\Types\Agent;
use Carno\Container\DI;
use Carno\Promise\Promise;

class Consul extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $dependencies = [Agent::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        // new loaders
        $loaderA = (new ConsulLD(DI::get(Agent::class), config()));
        $loaderG = (new ConsulLD(DI::get(Agent::class), config('global')));

        // connect to source
        $app->starting()->add(static function () use ($loaderA, $loaderG) {
            return Promise::all($loaderA->connect(), $loaderG->connect());
        });

        // disconnect from source
        $app->stopping()->add(static function () use ($loaderA, $loaderG) {
            return Promise::all($loaderA->disconnect(), $loaderG->disconnect());
        });
    }
}
