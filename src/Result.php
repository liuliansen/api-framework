<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2017-09-14
//+-------------------------------------------------------------
namespace api;

use api\traits\ErrorInfo;
use api\traits\Success;
use httprequest\HttpRequest;

/**
 * Class Result
 * @package extraapi
 */
abstract class Result
{
    use ErrorInfo;
    use Success;


    /**
     * @var Api
     */
    protected $api = null;

    /**
     * @var HttpRequest
     */
    protected $httpRequest = null;

    /**
     * @var object
     */
    protected $json = null; //响应解析后的json对象

    protected $code;
    protected $msg;

    /**
     * Result constructor.
     * @param  Api $api
     * @param  HttpRequest $httpRequest
     */
    public function __construct(Api $api, HttpRequest $httpRequest)
    {
        $this->api = $api;
        $this->httpRequest  = $httpRequest;
        $this->origResponse = $httpRequest->getResponseBody();
        $this->parse();
    }

    /**
     * <pre>
     * 解析body正文
     * 因为不同接口供应商，响应结果格式不同，
     * [比如有的直接返回json串，有的返回普通文本，而有的返回加密串]
     * 所以结果解析需要调用者实现
     */
    abstract protected function parse();


    /**
     * @return object|null
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

}
