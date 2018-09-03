<?php
/**
 * Global shaping manager
 * User: moyo
 * Date: 22/02/2018
 * Time: 2:52 PM
 */

namespace Carno\Serving\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Shaping\Control;
use Carno\Shaping\Shaper;

class Shaping extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $prerequisites = [Control::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        $app->stopping()->add(static function () {
            Control::retrieving(function (Shaper $shaper) {
                $shaper->shutdown();
            });
        });
    }
}
