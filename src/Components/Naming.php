<?php
/**
 * "APP" naming
 * User: moyo
 * Date: 13/12/2017
 * Time: 10:42 AM
 */

namespace Carno\Serving\Components;

use Carno\Console\App;
use Carno\Console\Component;
use Carno\Console\Contracts\Application;
use Carno\Console\Contracts\Bootable;

class Naming extends Component implements Bootable
{
    /**
     * @var int
     */
    protected $priority = 32;

    /**
     * @param Application|App $app
     */
    public function starting(Application $app) : void
    {
        // check exists

        if ($app->name()) {
            return;
        }

        // get from environment

        if (null !== $name = env('APP_NAME')) {
            $app->named($name);
            return;
        }

        // get from composer.json

        if (defined('CWD') && is_file($f = CWD . '/composer.json')) {
            $cj = json_decode(file_get_contents($f), true);
            if ($cj && $cn = $cj['name'] ?? false) {
                $app->named(str_replace(['-', '/'], '.', $cn));
                return;
            }
        }

        // use default

        $app->named('console.app.unnamed');
    }
}
