<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace aliyun\guzzle\subscriber;

use Psr\Http\Message\RequestInterface;

class Roa
{
    /** @var array Configuration settings */
    private $config;

    public function __construct($config)
    {
        $this->config = [
            'Version' => '2016-11-01',
            'accessKeyId' => '123456',
            'accessSecret' => '654321',
            'signatureMethod' => 'HMAC-SHA1',
            'signatureVersion' => '1.0',
            'dateTimeFormat' => 'Y-m-d\TH:i:s\Z',
        ];
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     * Called when the middleware is handled.
     *
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function ($request, array $options) use ($handler) {
            //暂未实现
            return $request;
        };
    }
}