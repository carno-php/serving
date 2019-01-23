<?php
/**
 * Extension manager
 * User: moyo
 * Date: 2019-01-15
 * Time: 19:12
 */

namespace Carno\Serving\Extension;

use Carno\Console\Bootstrap;
use Carno\Console\Configure;
use Carno\Serving\Contracts\Extensions;
use Carno\Serving\Contracts\Plugins;

class Managed
{
    use GKeeper;

    /**
     * @var Extensions[]
     */
    private $extensions = [];

    /**
     * @param Extensions $ext
     */
    public function load(Extensions $ext) : void
    {
        $this->extensions[get_class($ext)] = $ext;
    }

    /**
     * @param Configure $conf
     */
    public function options(Configure $conf) : void
    {
        foreach ($this->extensions as $ext) {
            $ext->options($conf);
        }
    }

    /**
     * @param Bootstrap $boot
     * @return Plugins[]
     */
    public function plugins(Bootstrap $boot) : array
    {
        $plugins = [];

        foreach ($this->extensions as $ext) {
            array_push($plugins, ...$ext->plugins($boot)->list());
        }

        return $plugins;
    }

    /**
     * @return array
     */
    public function components() : array
    {
        $mods = [];

        foreach ($this->extensions as $ext) {
            array_push($mods, ...$ext->components()->list());
        }

        return $mods;
    }
}
