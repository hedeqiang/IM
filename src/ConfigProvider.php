<?php

namespace TencentIM;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                IM::class => static function (ContainerInterface $container) {
                    return new IM($container->get(ConfigInterface::class)->get('im', []));
                },
            ],
            'publish' => [
                [
                    'id'          => 'config',
                    'description' => 'The config for im.',
                    'source'      => __DIR__.'/Config/im.php',
                    'destination' => BASE_PATH.'/config/autoload/im.php',
                ],
            ],
        ];
    }
}
