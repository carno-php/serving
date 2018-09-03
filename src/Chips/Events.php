<?php
/**
 * Serving events
 * User: moyo
 * Date: 2018/5/25
 * Time: 2:34 PM
 */

namespace Carno\Serving\Chips;

use Carno\Net\Events as NETEvs;

trait Events
{
    /**
     * @var NETEvs
     */
    private $evm = null;

    /**
     * @return NETEvs
     */
    public function events() : NETEvs
    {
        return $this->evm ?? $this->evm = new NETEvs;
    }
}
