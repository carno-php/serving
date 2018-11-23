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

    // cluster discovery
    public const RESOURCE_DISCOVERY = 'resource-dsv';
    public const SERVICE_DISCOVERY = 'service-dsv';
    public const IDENTITY_TAGS = 'tags';

    // consul related options
    public const CONSUL_AGENT = 'consul-agent';
    public const CONSUL_CONF = 'consul-conf';
    public const CONSUL_DSN = 'consul-dsn';
}
