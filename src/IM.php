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

    const ENDPOINT_TEMPLATE = 'https://console.tim.qq.com/%s/%s/%s?%s';

    const ENDPOINT_VERSION = 'v4';

    const ENDPOINT_FORMAT = 'json';

    protected $config;

    /****
     * IM constructor.
     *
     * @param  array  $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @param string $servername
     * @param string $command
     * @param array  $params
     *
     * @throws HttpException
     * @throws TenIMException
     *
     * @return array
     */
    public function send(string $servername, string $command, array $params = []): array
    {
        try {
            $result = $this->postJson($this->buildEndpoint($servername, $command), $params);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        if (0 === $result['ErrorCode'] && 'OK' === $result['ActionStatus']) {
            return $result;
        }

        throw new TenIMException('Tim REST API error: '.json_encode($result));
    }

    /**
     * Build endpoint url.
     *
     * @param string $servername
     * @param string $command
     *
     * @throws Exception
     *
     * @return string
     */
    protected function buildEndpoint(string $servername, string $command): string
    {
        $query = http_build_query([
            'sdkappid'    => $this->config->get('sdk_app_id'),
            'identifier'  => $this->config->get('identifier'),
            'usersig'     => $this->generateSign($this->config->get('identifier')),
            'random'      => random_int(0, 4294967295),
            'contenttype' => self::ENDPOINT_FORMAT,
        ]);

        return sprintf(self::ENDPOINT_TEMPLATE, self::ENDPOINT_VERSION, $servername, $command, $query);
    }

    /**
     * Generate Sign.
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
