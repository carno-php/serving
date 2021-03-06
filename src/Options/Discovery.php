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
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $conf->addOption(Options::RESOURCE_DISCOVERY, null, InputOption::VALUE_REQUIRED, 'Resource discovery', 'config');
        $conf->addOption(Options::SERVICE_DISCOVERY, null, InputOption::VALUE_REQUIRED, 'Service discovery', 'consul');
        $conf->addOption(Options::IDENTITY_TAGS, null, InputOption::VALUE_OPTIONAL, 'Cluster identity tags', '');
        $conf->addOption(Options::CONSUL_AGENT, null, InputOption::VALUE_OPTIONAL, 'Consul agent', '127.0.0.1:8500');
        $conf->addOption(Options::CONSUL_CONF, null, InputOption::VALUE_OPTIONAL, 'Consul conf', 'service/conf');
        $conf->addOption(Options::CONSUL_DSN, null, InputOption::VALUE_OPTIONAL, 'Consul dsn', 'service/dsn');
    }
}
