<?php
/**
 * Consul powering init
 * User: moyo
 * Date: 12/12/2017
 * Time: 6:20 PM
 */

namespace Carno\Serving\Components\Discovery;

use Carno\Cluster\Contracts\Tags;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Consul\Types\Agent;
use Carno\Consul\Types\Tagging;
use Carno\Container\DI;
use Carno\Serving\Contracts\Options;
use Carno\Serving\Exception\InvalidConsulAgentException;

class Consul extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 40;

    /**
     * @var array
     */
    protected $prerequisites = [Agent::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        if (!$app->input()->hasOption(Options::CONSUL_AGENT)) {
            return;
        }

        // .. AGENT

        $consul = $app->input()->getOption(Options::CONSUL_AGENT);

        $host = parse_url($consul, PHP_URL_HOST);
        $port = parse_url($consul, PHP_URL_PORT);

        if (empty($host)) {
            throw new InvalidConsulAgentException();
        }

        DI::set(Agent::class, new Agent($host, $port ?: 8500));

        // .. TAGGING

        $tags = $app->input()->getOption(Options::IDENTITY_TAGS);

        DI::set(Tagging::class, new Tagging(...($tags ? explode(',', $tags) : Tags::DEFAULT)));
    }
}
