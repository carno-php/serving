<?php
/**
 * Common option keys
 * User: moyo
 * Date: 2018/4/11
 * Time: 2:35 PM
 */

namespace Carno\Serving\Contracts;

interface Options
{
    // base
    public const DEBUG = 'debug';

    // metrics
    public const METRICS_PORT = 'metrics-port';
    public const METRICS_GATE = 'metrics-gate';

    // server listener
    public const LISTEN = 'listen';
    public const WORKERS = 'workers';

    // service discovery
    public const DISCOVER_MODE = 'discover-mode';
    public const CONSUL_AGENT = 'consul-agent';
    public const SERVICE_TAGS = 'tags';
}
