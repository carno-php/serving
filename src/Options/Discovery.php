<?php
/**
 * Resources discovery
 * User: moyo
 * Date: 26/03/2018
 * Time: 10:45 AM
 */

namespace Carno\Serving\Options;

use Carno\Console\Configure;
use Carno\Serving\Contracts\Options;
use Symfony\Component\Console\Input\InputOption;

trait Discovery
{
    /**
     * @param Configure $conf
     */
    protected function optionsRD(Configure $conf) : void
    {
        $conf->addOption(Options::DISCOVER_MODE, null, InputOption::VALUE_REQUIRED, 'Cluster mode', 'consul');
        $conf->addOption(Options::CONSUL_AGENT, null, InputOption::VALUE_OPTIONAL, 'Consul agent', '127.0.0.1:8500');
        $conf->addOption(Options::SERVICE_TAGS, null, InputOption::VALUE_OPTIONAL, 'Identify tags', '');
    }
}
