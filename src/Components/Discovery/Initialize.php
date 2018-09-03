<?php
/**
 * Discoverer init
 * User: moyo
 * Date: 12/12/2017
 * Time: 6:01 PM
 */

namespace Carno\Serving\Components\Discovery;

use Carno\Cluster\Discover\Adaptors\Consul;
use Carno\Cluster\Discover\Adaptors\DNS;
use Carno\Cluster\Discover\Discovered;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Consul\Types\Agent;
use Carno\Container\DI;
use Carno\DNS\Result;
use Carno\Serving\Contracts\Options;
use Carno\Serving\Exception\UnknownDiscoverModeException;

class Initialize extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 41;

    /**
     * @var array
     */
    protected $prerequisites = [Discovered::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        if ($app->input()->hasOption(Options::DISCOVER_MODE)) {
            switch ($app->input()->getOption(Options::DISCOVER_MODE)) {
                case 'consul':
                    $this->discovered(Agent::class, Consul::class);
                    break;
                case 'dns':
                    $this->discovered(Result::class, DNS::class);
                    break;
                default:
                    throw new UnknownDiscoverModeException;
            }
        }
    }

    /**
     * @param string $depend
     * @param string $adaptor
     */
    private function discovered(string $depend, string $adaptor)
    {
        if (class_exists($depend)) {
            DI::set(Discovered::class, DI::object($adaptor));
        } else {
            logger('serving')->warning('Discovery driver not provided', ['adaptor' => $adaptor]);
        }
    }
}
