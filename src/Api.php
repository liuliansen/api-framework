<?php
//+-------------------------------------------------------------
//| 第三方接口Api抽象基类
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2017-11-21
//+-------------------------------------------------------------
namespace api;

use httprequest\HttpRequest;
use httprequest\Url;

/**
 * Class Api
 * @method mixed   getErrCode()      获取错误码
 * @method string  getError()        获取错误信息
 * @method bool    success()         是否调用成功
 * @method string  getOrigResponse() 获取原始响应正文
 * @method mixed   getJson()         获取正文解析出来的json（如果有）
 * @method array|bool getJsonArray() 将响应的json数据转换成array
 * @method mixed   getCode()         获取结果处理码（如果有）
 * @method string  getMsg()          获取结果处理信息（如果有）
 * @method HttpRequest getHttpRequest()   获取请求对象
 * @package extraapi
 */
abstract class Api
{

    /**
     * @var Url
     */
    protected $url = null;

    /**
     * @var callable
     */
    protected $callback;

    protected $debug  = false; //是否开启调试

    /**
     * @var Result
     */
    protected $result = null;

    protected $resultCls = '';

    public function __construct($callback = null)
    {
        $this->init();
        $this->callback = $callback;
    }

    /**
     * 初始方法
     */
    protected function init()
    {
        //Add your init code in here.
    }

    /**
     * <pre>
     * HttpRequest请求前置方法，如果有需要在子类中进行覆盖
     * 一般是用来做一些curl对象的个性设置
     * @param HttpRequest $req
     */
    protected function beforeRequest(HttpRequest $req)
    {
        //Add your code in here if you need do something before the HttpRequest::request.
    }

    /**
     * <pre>
     * HttpRequest请求后置方法，如果有需要在子类中进行覆盖
     */
    protected function afterRequest()
    {
        //Add your code in here if you need do something after the HttpRequest request.
    }


    /**
     * @param  bool $debug
     * @return void
     * @throws
     */
    public function request($debug = false)
    {
        $httpReq = new HttpRequest($this->url);
        $this->beforeRequest($httpReq);
        $httpReq->request($debug || $this->debug);
        if(!$this->resultCls) {
            throw new \Exception(static::class.'未设置结果类');
        }
        $rf = new \ReflectionClass($this->resultCls);
        $this->result = $rf->newInstanceArgs([ $this,$httpReq ]);
        if(!($this->result instanceof Result)){
            throw new \Exception(static::class.'结果类必须是'.Result::class.'的子孙类');
        }
        $this->afterRequest();
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->result,$name],$arguments);
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

}
