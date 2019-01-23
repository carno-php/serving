<?php
/**
 * Extensional components
 * User: moyo
 * Date: 2019-01-21
 * Time: 10:09
 */

namespace Carno\Serving\Extension;

final class Components
{
    /**
     * @var string[]
     */
    private $mods = [];

    /**
     * Components constructor.
     * @param string ...$mods
     */
    public function __construct(string ...$mods)
    {
        $this->mods = $mods;
    }

    /**
     * @return string[]
     */
    public function list() : array
    {
        return $this->mods;
    }
}
