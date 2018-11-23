<?php
/**
 * Shared resources
 * User: moyo
 * Date: 2018/8/29
 * Time: 6:12 PM
 */

namespace Carno\Serving;

use Carno\Serving\Components\Caching;
use Carno\Serving\Components\Config;
use Carno\Serving\Components\Discovery;
use Carno\Serving\Components\Environment;
use Carno\Serving\Components\Logging;
use Carno\Serving\Components\Monitor;
use Carno\Serving\Components\Naming;
use Carno\Serving\Components\Runtime;
use Carno\Serving\Components\Shaping;

interface Shared
{
    public const COMPONENTS = [
        Environment::class,
        Naming::class,

        Runtime\ActiveGC::class,

        Discovery\Classify::class,
        Discovery\Assigning::class,
        Discovery\Resourced::class,
        Discovery\Consul::class,

        Monitor\Daemons::class,
        Monitor\Runtime::class,
        Monitor\Pooled::class,

        Config\Initialize::class,
        Config\Consul::class,

        Logging::class,
        Caching::class,

        Shaping::class,
    ];
}
