<?php

namespace TencentIM;

interface IMInterface
{
    public function send(string $servername, string $command, array $params = []): array;
}
