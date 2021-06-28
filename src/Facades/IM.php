<?php

/*
 * This file is part of the hedeqiang/ten-im.
 *
 * (c) hedeqiang<laravel_code@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hedeqiang\TenIM\Facades;

use Illuminate\Support\Facades\Facade;

/****
 * Class IM
 *
 * @package Hedeqiang\TenIM\Facades
 */
class IM extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'im';
    }

    /**
     * Return the facade accessor.
     *
     * @return \Hedeqiang\TenIM\IM
     */
    public static function im(): \Hedeqiang\TenIM\IM
    {
        return app('im');
    }
}
