<?php
/**
 * Hardware info getter
 * User: moyo
 * Date: 24/10/2017
 * Time: 4:10 PM
 */

namespace Carno\Serving\Chips;

trait HWIGet
{
    /**
     * @var int
     */
    private $numCPUs = null;

    /**
     * @return int
     */
    private function numCPUs() : int
    {
        if ($this->numCPUs) {
            return $this->numCPUs;
        }

        if (defined('SWOOLE_CPU_NUM')) {
            $this->numCPUs = SWOOLE_CPU_NUM;
        } elseif (is_file('/proc/cpuinfo')) {
            preg_match_all('/^processor/m', file_get_contents('/proc/cpuinfo'), $matches);
            $this->numCPUs = count($matches[0] ?? [0]);
        }

        return $this->numCPUs ?? 1;
    }
}
