<?php
/**
 * Configure via consul
 * User: moyo
 * Date: 13/12/2017
 * Time: 10:17 AM
 */

namespace Carno\Serving\Components\Config;

use Carno\Config\Loaders\Consul as Loader;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Consul\Types\Agent;
use Carno\Container\DI;
use Carno\Promise\Promise;
use Carno\Promise\Promised;
use Carno\Serving\Contracts\Options;
use Carno\Serving\Contracts\ScopedConf;

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
        $conf = $app->input()->getOption(Options::CONSUL_CONF);
        $dsn = $app->input()->getOption(Options::CONSUL_DSN);

        $configures = [
            [config(), $conf],
            [config(ScopedConf::GLOBAL), $conf],
            [config(ScopedConf::DSN)->assigned(''), $dsn],
        ];

        $this->initializing($app, ...$configures);
    }

    /**
     * @param Application $app
     * @param array ...$options
     */
    private function initializing(Application $app, array ...$options)
    {
        $loaders = [];

        // creating loaders
        foreach ($options as $option) {
            $loaders[] = new Loader(DI::get(Agent::class), ...$option);
        }

        // connect to source
        $app->starting()->add(static function () use ($loaders) {
            return Promise::all(...self::actioning('connect', ...$loaders));
        });

        // disconnect from source
        $app->stopping()->add(static function () use ($loaders) {
            return Promise::all(...self::actioning('disconnect', ...$loaders));
        });
    }

    /**
     * @param string $method
     * @param Loader ...$loaders
     * @return Promised[]
     */
    private static function actioning(string $method, Loader ...$loaders) : array
    {
        $waits = [];

        foreach ($loaders as $loader) {
            /**
             * @see Loader::connect
             * @see Loader::disconnect
             */
            $waits[] = $loader->$method();
        }

        return $waits;
    }
}
