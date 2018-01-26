<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2017-09-14
//+-------------------------------------------------------------

namespace  api\traits;

/**
 * Trait ErrorInfo
 * @package traits
 */
trait ErrorInfo
{
    protected $errcode = '';  //接口响应错误码
    protected $error   = '';  //接口响应错误信息

    /**
     * 获取接口响应错误码
     * @return string -1 代表响应结果json解析失败
     */
    public function getErrCode()
    {
        return $this->errcode;
    }

    /**
     * 获取接口响应错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
