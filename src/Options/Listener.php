<?php
/**
 * Server listener
 * User: moyo
 * Date: 2018/8/30
 * Time: 11:51 AM
 */

namespace Carno\Serving\Options;

use Carno\Console\Configure;
use Carno\Serving\Contracts\Options;
use Symfony\Component\Console\Input\InputOption;

trait Listener
{
    /**
     * @param Configure $conf
     */
    protected function optionsSL(Configure $conf) : void
    {
        $conf->addOption(Options::LISTEN, null, InputOption::VALUE_OPTIONAL, 'Server listen bind', ':0');
        $conf->addOption(Options::WORKERS, null, InputOption::VALUE_OPTIONAL, 'Server workers num', 0);
    }
}
