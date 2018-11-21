<?php
/**
 * Logs manager init
 * User: moyo
 * Date: 13/12/2017
 * Time: 10:19 AM
 */

namespace Carno\Serving\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Container\DI;
use Carno\Log\Configure;
use Carno\Log\Connections;
use Carno\Log\Environment;
use Carno\Log\Instances;
use Carno\Log\Logger;
use Carno\Serving\Contracts\Options;

class Logging extends Component implements Bootable
{
    /**
     * @var array
     */
    protected $prerequisites = [Logger::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        if ($app->input()->hasOption(Options::SERVICE_TAGS)) {
            $tags = $app->input()->getOption(Options::SERVICE_TAGS);
        }

        DI::set(Environment::class, $e = new Environment($app->name(), $tags ?? ''));

        DI::set(Connections::class, $c = new Connections);

        Instances::configuration(new Configure($e, $c));

        $app->stopping()->add(function () use ($c) {
            return $c->release();
        });
    }
}
