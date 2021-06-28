<?php

/*
 * This file is part of the hedeqiang/ten-im.
 *
 * (c) hedeqiang<laravel_code@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hedeqiang\TenIM;

/***
 * Class ServiceProvider
 *
 * @package Hedeqiang\TenIM
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/im.php' => config_path('im.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(IM::class, function () {
            return new IM(config('im'));
        });

        $this->app->alias(IM::class, 'im');
    }

    /***
     * @return string[]
     */
    public function provides(): array
    {
        return [IM::class, 'im'];
    }
}
