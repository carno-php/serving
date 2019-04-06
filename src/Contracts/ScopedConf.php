<?php
/**
 * Conf scopes
 * User: moyo
 * Date: 2018/11/22
 * Time: 11:57 AM
 */

namespace Carno\Serving\Contracts;

interface ScopedConf
{
    public const COM = 'global';
    public const DSN = 'conf:scoped:dsn';
}
