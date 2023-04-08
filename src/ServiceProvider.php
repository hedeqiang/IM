<?php

namespace TencentIM;

/***
 * Class ServiceProvider
 *
 * @package TencentIM
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
