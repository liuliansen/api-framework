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
use traits\OrigResponse;
use utils\LogHelper;

/**
 * Class Result
 * @package extraapi
 */
abstract class Result
{
    use ErrorInfo;
    use Success;
    use OrigResponse;


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
     * @var LogHelper
     */
    protected $logger      = null;

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
        if($this->logger){
            $this->logger->log($this->api->getUrl().PHP_EOL.$httpRequest->getOrigResponse());
        }
        $this->parse();
    }


    /**
     * 设置日志对象
     * @param LogHelper $logger
     */
    public function setLogger(LogHelper $logger)
    {
        $this->logger = $logger;
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
     * 将响应的json数据转换成array
     * @return array|bool
     */
    public function getJsonArray()
    {
        if($this->json){
            return json_decode($this->json,true);
        }
        return false;
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


    /**
     * 设置错误信息
     * @param string $file
     * @param int    $line
     * @param string $msg
     * @param int $code
     */
    public function setErrorInfo($file,$line,$msg,$code = 503)
    {
        $this->success = false;
        $this->errcode = $code;
        $this->error   = $msg;
        if($this->logger){
            $log = $this->api->getUrl()->getBaseUrl().PHP_EOL . $file. "::". $line. PHP_EOL. $msg;
            $this->logger->error($log);
        }
    }


}
