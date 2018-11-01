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
use api\traits\OrigResponse;
use httprequest\HttpRequest;

use utils\Logger;

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
     * @var Logger
     */
    protected $logger      = null;

    /**
     * Result constructor.
     * @param  Api $api
     * @param  HttpRequest $httpRequest
     * @param Logger|null $logger
     */
    public function __construct(Api $api, HttpRequest $httpRequest,Logger $logger = null)
    {
        $this->api = $api;
        $this->httpRequest  = $httpRequest;
        $this->origResponse = $httpRequest->getResponseBody();
        $this->logger = $logger;
        if($this->logger){
            $resHeader = json_encode($httpRequest->getResponseHeader()->get(),JSON_UNESCAPED_UNICODE);
            $resBody   = $httpRequest->getResponseBody();
            $extData   = $this->api->getLogExtData();
            if(is_array($extData) || is_object($extData)){
                $extData  = json_encode($extData,JSON_UNESCAPED_UNICODE);
            }
            $log = $this->api->getUrl()."\t[{$extData}]\t[{$resHeader}]\t[{$resBody}]";
            $this->logger->log($log);
        }
        $this->parse();
    }


    /**
     * 设置日志对象
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
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
            return json_decode($this->origResponse,true);
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
            $resHeader = json_encode($this->httpRequest->getResponseHeader()->get(),JSON_UNESCAPED_UNICODE);
            $resBody   = $this->httpRequest->getResponseBody();
            $extData   = $this->api->getLogExtData();
            if(is_array($extData) || is_object($extData)){
                $extData  = json_encode($extData,JSON_UNESCAPED_UNICODE);
            }
            $log = $this->api->getUrl()."\t[{$extData}]\t[{$msg}]\t[{$file}::{$line}]\t[{$resHeader}]\t[{$resBody}]";
            $this->logger->error($log);

        }
    }


}
