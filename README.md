<h1 align="center"> 腾讯云 IM 服务端 SDK for PHP </h1>

<p align="center">腾讯云 IM.</p>

## 安装

```shell
$ composer require zys5210/tencent_im -vvv
```

> 说明：本项目默认会引入 [tencent/tls-sig-api-v2](https://github.com/tencentyun/tls-sig-api-v2-php) 扩展包，所以你不需要手动引入 `tencent/tls-sig-api-v2`,否则可能会造成依赖冲突

## 配置
使用本扩展前需要登录 [即时通信 IM 控制台](https://console.cloud.tencent.com/avc) 创建应用，配置管理员、获取 app_id、Key 等关键信息

更多请查看并熟读 [即时通信 IM 服务端API](https://cloud.tencent.com/document/product/269/32688)

[REST API 接口列表](https://cloud.tencent.com/document/product/269/1520)

## 使用



> 其中 `send` 方法接收三个参数。第一个参数 $servicename : 内部服务名，不同的 servicename 对应不同的服务类型；第二个参数 `$command`：命令字，与 servicename 组合用来标识具体的业务功能；第三个参数为请求包主体 

> 示例：`v4/im_open_login_svc/account_import`，其中 `im_open_login_svc` 为 `servicename`； `account_import` 为 `command`


请求包示例：
```php
{
    "From_Account":"id",
    "ProfileItem":
    [
        {
            "Tag":"Tag_Profile_IM_Nick",
            "Value":"MyNickName"
        }
    ]
}
```

###  导入单个帐号
```php

require __DIR__ .'/vendor/autoload.php';

use TencentIM\IM;

$config = [
    'sdk_app_id' => '',
    'identifier' => '',
    'secret_key' => '',
];
$im = new IM($config);
$params = [
    'Identifier' => "test",
    'Nick'       => 'test',
    'FaceUrl'    => '',
];

print_r($im->send('im_open_login_svc','account_import',$params));
```


### 获取用户在线状态
```php
<?php
$params = [
    'To_Account' => ['test']
];

print_r($im->send('openim','querystate',$params));
```
返回示例
```php
{
	"ActionStatus": "OK",
	"ErrorInfo": "",
	"ErrorCode": 0,
	"QueryResult": [{
		"To_Account": "1",
		"State": "Offline"
	}]
}
```
### 设置资料
```php
$params = [
    'From_Account' => 'test',
        'ProfileItem' => [
            ['Tag' => 'Tag_Profile_IM_Nick', 'Value' => 'test'],
            ['Tag' => 'Tag_Profile_IM_Gender', 'Value' => 'Gender_Type_Male'],
            ['Tag' => 'Tag_Profile_IM_BirthDay', 'Value' => 19940410],
            ['Tag' => 'Tag_Profile_IM_SelfSignature', 'Value' => '程序人生的寂静欢喜'],
            ['Tag' => 'Tag_Profile_IM_Image', 'Value' => 'https://upyun.laravelcode.cn/upload/avatar/1524205770e4fbfbff-86ae-3bf9-b7b8-e0e70ce14553.png'],
        ],
];

print_r($im->send('profile','portrait_set',$params));
```

返回示例：
```php
{
	"ActionStatus": "OK",
	"ErrorCode": 0,
	"ErrorInfo": "",
	"ErrorDisplay": ""
}
```

### 单发单聊消息
```php
$params = [
    'SyncOtherMachine' => 1, // 消息不同步至发送方
    'From_Account' => '1',
    'To_Account' => '2',
    'MsgRandom' => 1287657,
    'MsgTimeStamp' => 1557387418,
    'MsgBody' => [
        [
            'MsgType' => 'TIMTextElem',
            'MsgContent' => [
                'Text' => '晚上去撸串啊'
            ]
        ]
    ]
];

print_r($im->send('openim','sendmsg',$params));
```

返回示例：
```php
{
    "ActionStatus":"OK",
    "ErrorInfo":"",
    "ErrorCode":0,
    "MsgTime":1573179125,
    "MsgKey":"748144182_1287657_1573179125"
}
```

## 在 Hyperf 中使用
#### 发布配置文件
```php
php bin/hyperf.php vendor:publish zys5210/tencent_im
```

##### 编写 .env 文件
```
SDK_APP_ID=
IDENTIFIER=
SECRET_KEY=
```

##### 使用
```php
use TencentIM\IM;
use Hyperf\Utils\ApplicationContext;

$response = ApplicationContext::getContainer()->get(IM::class)->send($servername,$command,$params);
```




## 在 Laravel 中使用
#### 发布配置文件

```php
php artisan vendor:publish --provider="TencentIM\ServiceProvider"
```

##### 编写 .env 文件
```
SDK_APP_ID=
IDENTIFIER=
SECRET_KEY=
```

### 方法参数注入

```php
use TencentIM\IM;

public function index(IM $im)
{
    $params = [
        'SyncOtherMachine' => 1, // 消息不同步至发送方
        'From_Account' => '1',
        'To_Account' => '2',
        'MsgRandom' => 1287657,
        'MsgTimeStamp' => 1557387418,
        'MsgBody' => [
            [
                'MsgType' => 'TIMTextElem',
                'MsgContent' => [
                    'Text' => '晚上去撸串啊'
                ]
            ]
        ]
    ];
    $response = $im->send('openim','sendmsg',$params);
}
```
### 服务名访问
```php
public function index()
{
    $params = [
        'SyncOtherMachine' => 1, // 消息不同步至发送方
        'From_Account' => '1',
        'To_Account' => '2',
        'MsgRandom' => 1287657,
        'MsgTimeStamp' => 1557387418,
        'MsgBody' => [
            [
                'MsgType' => 'TIMTextElem',
                'MsgContent' => [
                    'Text' => '晚上去撸串啊'
                ]
            ]
        ]
    ];
    $response = app('im')->send('openim','sendmsg',$params);
}
```

### Facades 门面使用(可以提示)
```php
use TencentIM\Facades\IM;

$params = [
        'SyncOtherMachine' => 1, // 消息不同步至发送方
        'From_Account' => '1',
        'To_Account' => '2',
        'MsgRandom' => 1287657,
        'MsgTimeStamp' => 1557387418,
        'MsgBody' => [
            [
                'MsgType' => 'TIMTextElem',
                'MsgContent' => [
                    'Text' => '晚上去撸串啊'
                ]
            ]
        ]
    ];
$response = IM::im()->send('openim','sendmsg',$params);
```


更多用法请参考 [REST API 接口列表](https://cloud.tencent.com/document/product/269/1520)

TODO

## License

MIT
