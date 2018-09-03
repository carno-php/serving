<?php
/**
 * Metrics exporter
 * User: moyo
 * Date: 2018/8/29
 * Time: 5:27 PM
 */

namespace Carno\Serving\Options;

use Carno\Console\Configure;
use Carno\Serving\Contracts\Options;
use Symfony\Component\Console\Input\InputOption;

trait Metrics
{
    /**
     * @param Configure $conf
     */
    protected function optionsME(Configure $conf) : void
    {
        $conf->addOption(Options::METRICS_PORT, null, InputOption::VALUE_OPTIONAL, 'Port of metrics exporter', 9102);
        $conf->addOption(Options::METRICS_GATE, null, InputOption::VALUE_OPTIONAL, 'Address of metrics gateway', '');
    }
}
