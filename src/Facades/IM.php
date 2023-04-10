<?php

namespace TencentIM\Facades;

use Illuminate\Support\Facades\Facade;

/****
 * Class IM
 *
 * @package TencentIM\Facades
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
     * @return TencentIM\IM
     */
    public static function im(): \TencentIM\IM
    {
        return app('im');
    }
}
