# aliyun-guzzle

鉴于官方API过度设计，所以这个中间件是 GuzzleHttp 专用的，支持阿里云大部分API请求。

For license information check the [LICENSE](LICENSE)-file.

[![Latest Stable Version](https://poser.pugx.org/aliyunapi/guzzle-aliyun-subscriber/v/stable.png)](https://packagist.org/packages/aliyunapi/guzzle-aliyun-subscriber)
[![Total Downloads](https://poser.pugx.org/aliyunapi/guzzle-aliyun-subscriber/downloads.png)](https://packagist.org/packages/aliyunapi/guzzle-aliyun-subscriber)
[![Reference Status](https://www.versioneye.com/php/aliyunapi:guzzle-aliyun-subscriber/reference_badge.svg)](https://www.versioneye.com/php/guzzle-aliyun-subscriber/references)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist aliyunapi/guzzle-aliyun-subscriber
```

or add

```
"aliyunapi/guzzle-aliyun-subscriber": "~1.0"
```

to the require section of your composer.json.


使用
------------
````
$stack = HandlerStack::create();

//跟guzzlephp普通用法唯一的区别就是这里吧中间件加载进来，他会自动帮你签名重新包装请求参数。
$middleware = new \xutl\guzzle\subscriber\aliyun\Rpc([
    'accessKeyId' => '123456',
    'accessSecret' => '654321',
]);
$stack->push($middleware);

//这里设置 网关地址，数组参数请参见 http://docs.guzzlephp.org/en/latest/request-options.html
$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://live.aliyuncs.com/',
    'handler' => $stack,
]);

//查询参数  https://help.aliyun.com/document_detail/35412.html 这个页面列出了几个参数就在数组提交几个参数
$res = $client->get('/', [
    'query' => [
        'Action' => 'DescribeLiveStreamOnlineUserNum',
        'DomainName' => 'live.aaa.tv',
        'AppName' => 'live',
        'StreamName' => 'bbb',
        ]
]);

print_r($res->getBody()->getContents());
````