<?php
/**
 * Dynamic environments
 * User: moyo
 * Date: 27/12/2017
 * Time: 10:20 AM
 */

namespace Carno\Serving\Components;

use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;
use Carno\Env\Vars;
use Carno\Serving\Contracts\Options;

class Environment extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 30;

    /**
     * @var array
     */
    protected $prerequisites = [Vars::class];

    /**
     * @param Application $app
     */
    public function starting(Application $app) : void
    {
        // ".env" loading
        defined('CWD') && is_file($f = CWD . '/.env') && Vars::load($f);

        // "debug" checking
        if ($app->input()->hasOption(Options::DEBUG)) {
            $in = $app->input()->getOption(Options::DEBUG);
            Vars::export('DEBUG', (int)filter_var($in ?? 1, FILTER_VALIDATE_BOOLEAN));
        }

        // ".env" watching
        function_exists('config') &&
        $app->starting()->add(static function () {
            config()->watching('.env', static function ($val) {
                foreach (explode("\n", $val ?: '') as $exp) {
                    if (($sep = strpos($exp, '=')) > 0) {
                        Vars::export(substr($exp, 0, $sep), substr($exp, $sep + 1));
                    } else {
                        Vars::unset($exp);
                    }
                }
            });
        });
    }
}
