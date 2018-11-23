<?php
/**
 * Cluster discoveries init
 * User: moyo
 * Date: 12/12/2017
 * Time: 6:01 PM
 */

namespace Carno\Serving\Components\Discovery;

use Carno\Cluster\Classify\Classified;
use Carno\Cluster\Classify\Scenes;
use Carno\Cluster\Discovery\Adaptors\Config;
use Carno\Cluster\Discovery\Adaptors\Consul;
use Carno\Cluster\Discovery\Adaptors\DNS;
use Carno\Cluster\Discovery\Discovered;
use Carno\Config\Config as Conf;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Consul\Types\Agent;
use Carno\Container\DI;
use Carno\DNS\Result;
use Carno\Serving\Contracts\Options;
use Carno\Serving\Contracts\ScopedConf;
use Carno\Serving\Exception\UnavailableDiscoveryException;
use Carno\Serving\Exception\UnknownDiscoveryException;

class Assigning extends Component implements Bootable
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
     * @var array
     */
    protected $dependencies = [Classified::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        $this->classified($app, Options::RESOURCE_DISCOVERY, DI::get(Classified::class), Scenes::RESOURCE);
        $this->classified($app, Options::SERVICE_DISCOVERY, DI::get(Classified::class), Scenes::SERVICE);
    }

    /**
     * @param Application $app
     * @param string $option
     * @param Classified $classifier
     * @param string $scene
     */
    private function classified(Application $app, string $option, Classified $classifier, string $scene) : void
    {
        if ($app->input()->hasOption($option)) {
            switch ($app->input()->getOption($option)) {
                case 'config':
                    $dsv = $this->discovered('config', Conf::class, static function (string $scene) {
                        switch ($scene) {
                            case Scenes::RESOURCE:
                                $source = config(ScopedConf::DSN);
                                break;
                            case Scenes::SERVICE:
                                $source = config(ScopedConf::GLOBAL);
                                break;
                        }
                        return new Config($source ?? config());
                    }, $scene);
                    break;
                case 'consul':
                    $dsv = $this->discovered('consul', Agent::class, static function () {
                        return new Consul(DI::get(Agent::class));
                    });
                    break;
                case 'dns':
                    $dsv = $this->discovered('dns', Result::class, static function () {
                        return new DNS;
                    });
                    break;
                default:
                    throw new UnknownDiscoveryException;
            }

            $dsv && $classifier->assigning($scene, $dsv);
        }
    }

    /**
     * @param string $named
     * @param string $depend
     * @param callable $creator
     * @param mixed ...$params
     * @return Discovered
     */
    private function discovered(string $named, string $depend, callable $creator, ...$params) : Discovered
    {
        if (class_exists($depend)) {
            return call_user_func($creator, ...$params);
        }

        logger('serving')->warning('Discovery driver not provided', ['adaptor' => $named]);

        throw new UnavailableDiscoveryException;
    }
}
