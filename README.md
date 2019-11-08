<h1 align="center"> 腾讯云 IM 服务端 SDK for PHP </h1>

<p align="center">腾讯云 IM.</p>


## Installing

```shell
$ composer require hedeqiang/ten-im -vvv
```

## Seetings
使用本扩展前需要登录 [即时通信 IM 控制台](https://console.cloud.tencent.com/avc) 创建应用，配置管理员、获取 app_id、Key 等关键信息

更多请查看并熟读 [即时通信 IM 服务端API](https://cloud.tencent.com/document/product/269/32688)

[REST API 接口列表](https://cloud.tencent.com/document/product/269/1520)

## Usage
```php
<?php

require __DIR__ .'/vendor/autoload.php';

use Hedeqiang\TenIM\IM;

$config = [
    'sdk_app_id' => '14002***',
    'identifier' => 'hedeqiang',
    'secret_key' => 'a56e6938cb1a8856d15*****',
];
$im = new IM($config);

$params = [
    'To_Account' => ['1']
];
$params = [
'From_Account' => '1',
    'ProfileItem' => [
        ['Tag' => 'Tag_Profile_IM_Nick', 'Value' => 'hedeqiang'],
        ['Tag' => 'Tag_Profile_IM_Gender', 'Value' => 'Gender_Type_Male'],
        ['Tag' => 'Tag_Profile_IM_BirthDay', 'Value' => 19940410],
        ['Tag' => 'Tag_Profile_IM_SelfSignature', 'Value' => '程序人生的寂静欢喜'],
        ['Tag' => 'Tag_Profile_IM_Image', 'Value' => 'https://upyun.laravelcode.cn/upload/avatar/1524205770e4fbfbff-86ae-3bf9-b7b8-e0e70ce14553.png'],
    ],
];


print_r($im->send('openim','querystate',$params));
```
> 其中 `send` 方法接收三个参数。第一个参数 $servicename : 内部服务名，不同的 servicename 对应不同的服务类型;第二个参数 `$command`:命令字，与 servicename 组合用来标识具体的业务功能;第三个参数为请求包主体 
> 示例：`v4/im_open_login_svc/account_import`，其中im_open_login_svc为servicename

TODO

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/hedeqiang/tenIM/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/hedeqiang/tenIM/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT