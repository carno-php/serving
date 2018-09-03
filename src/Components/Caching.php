<?php
/**
 * Caches manager init
 * User: moyo
 * Date: 13/12/2017
 * Time: 10:18 AM
 */

namespace Carno\Serving\Components;

use Carno\Cache\Eviction;
use Carno\Cache\Refreshing;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;

class Caching extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $prerequisites = [Refreshing::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        DI::set(Eviction::class, $eviction = new Eviction);
        DI::set(Refreshing::class, $refresher = new Refreshing);

        $app->stopping()->add(static function () use ($eviction, $refresher) {
            $eviction->shutdown();
            $refresher->shutdown();
        });
    }
}
