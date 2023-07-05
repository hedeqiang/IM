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

use Exception;
use Hedeqiang\TenIM\Exceptions\HttpException;
use Hedeqiang\TenIM\Exceptions\TenIMException;
use Hedeqiang\TenIM\Traits\HasHttpRequest;
use Tencent\TLSSigAPIv2;

class IM implements IMInterface
{
    use HasHttpRequest;

    const ENDPOINT_TEMPLATES = [
        'zh'  => 'https://console.tim.qq.com/%s/%s/%s?%s',
        'sgp' => 'https://adminapisgp.im.qcloud.com/%s/%s/%s?%s',
        'kr'  => 'https://adminapikr.im.qcloud.com/%s/%s/%s?%s',
        'ger' => 'https://adminapiger.im.qcloud.com/%s/%s/%s?%s',
        'ind' => 'https://adminapiind.im.qcloud.com/%s/%s/%s?%s',
        'usa' => 'https://adminapiusa.im.qcloud.com/%s/%s/%s?%s',
    ];

    const ENDPOINT_VERSION = 'v4';
    const ENDPOINT_FORMAT = 'json';

    protected $config;

    /**
     * IM constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Send request to the IM server.
     *
     * @param string $serverName
     * @param string $command
     * @param array  $params
     *
     * @throws TenIMException|HttpException
     * @throws Exception
     *
     * @return array
     */
    public function send(string $serverName, string $command, array $params = []): array
    {
        $endpoint = $this->buildEndpoint($serverName, $command);
        $result = $this->postJson($endpoint, $params);

        return $result;
    }

    /**
     * Build the endpoint URL.
     *
     * @param string $serverName
     * @param string $command
     *
     * @throws Exception
     *
     * @return string
     */
    protected function buildEndpoint(string $serverName, string $command): string
    {
        $region = $this->config->get('region', 'zh');
        $endpointTemplate = self::ENDPOINT_TEMPLATES[$region];

        $query = http_build_query([
            'sdkappid'    => $this->config->get('sdk_app_id'),
            'identifier'  => $this->config->get('identifier'),
            'usersig'     => $this->generateSign($this->config->get('identifier')),
            'random'      => random_int(0, 4294967295),
            'contenttype' => self::ENDPOINT_FORMAT,
        ]);

        return sprintf(
            $endpointTemplate,
            self::ENDPOINT_VERSION,
            $serverName,
            $command,
            $query
        );
    }

    /**
     * Generate the user signature.
     *
     * @param string $identifier
     * @param int    $expires
     *
     * @throws Exception
     *
     * @return string
     */
    protected function generateSign(string $identifier, int $expires = 15552000): string
    {
        $api = new TLSSigAPIv2($this->config->get('sdk_app_id'), $this->config->get('secret_key'));

        return $api->genUserSig($identifier, $expires);
    }
}
