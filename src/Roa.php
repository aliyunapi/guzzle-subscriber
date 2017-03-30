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

    private static $headerSeparator = "\n";

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
            $request = $this->onBefore($request);
            return $handler($request, $options);
        };
    }

    /**
     * 请求前调用
     * @param RequestInterface $request
     * @return RequestInterface
     */
    private function onBefore(RequestInterface $request)
    {
        $headers = $request->getHeaders();

        $headers["Date"] = gmdate($this->config['dateTimeFormat']);
        $headers["Accept"] = 'application/octet-stream';
        $headers["x-acs-signature-method"] = $this->config['signatureMethod'];
        $headers["x-acs-signature-version"] = $this->config['signatureVersion'];

        if(isset($this->config['regionId'])){
            $headers["x-acs-region-id"] = $this->config['regionId'];
        }

        $content = $request->getBody()->getContents();
        if ($content != null) {
            $headers["Content-MD5"] = base64_encode(md5(json_encode($content),true));
        }
        $headers["Content-Type"] = "application/octet-stream;charset=utf-8";


        $signString = $request->getMethod() . self::$headerSeparator;
        if (isset($headers["Accept"])) {
            $signString = $signString . $headers["Accept"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Content-MD5"])) {
            $signString = $signString . $headers["Content-MD5"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Content-Type"])) {
            $signString = $signString . $headers["Content-Type"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Date"])) {
            $signString = $signString . $headers["Date"];
        }
        $signString = $signString . self::$headerSeparator;


        //etc....
        return $request;
        $uri = $this->replaceOccupiedParameters();
        $signString = $signString.$this->buildCanonicalHeaders();
        $queryString = $this->buildQueryString($uri);
        $signString .= $queryString;

        $requestUrl = $request->getProtocol()."://".$domain.$queryString;
        return $requestUrl;

        $request = $request->withHeader('Authorization', "acs ".$this->config['accessKeyId'].":"
            .$iSigner->signString($signString, $this->config['accessSecret']));
        return $request;
    }
}