<?php
/**
 * Cluster resources init
 * User: moyo
 * Date: 12/12/2017
 * Time: 6:28 PM
 */

namespace Carno\Serving\Components\Discovery;

use Carno\Cluster\Classify\Classified;
use Carno\Cluster\Resources;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;

class Resourced extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 42;

    /**
     * @var array
     */
    protected $dependencies = [Classified::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        DI::set(Resources::class, $resources = new Resources(DI::get(Classified::class)));

        $app->starting()->add(static function () use ($resources) {
            return $resources->startup()->ready();
        });

        $app->stopping()->add(static function () use ($resources) {
            return $resources->release();
        });
    }
}
