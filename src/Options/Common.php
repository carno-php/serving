<?php
/**
 * Common arguments
 * User: moyo
 * Date: 2018/8/30
 * Time: 10:43 AM
 */

namespace Carno\Serving\Options;

use Carno\Console\Configure;
use Carno\Serving\Contracts\Options;
use Symfony\Component\Console\Input\InputOption;

trait Common
{
    /**
     * @param Configure $conf
     */
    protected function optionsCA(Configure $conf) : void
    {
        $conf->addOption(Options::DEBUG, null, InputOption::VALUE_OPTIONAL, 'Debugging mode', false);
    }
}
