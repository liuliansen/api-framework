<?php
//+-------------------------------------------------------------
//| 
//+-------------------------------------------------------------
//| Author Liu LianSen <liansen@d3zz.com> 
//+-------------------------------------------------------------
//| Date 2017-09-14
//+-------------------------------------------------------------
namespace  traits;

trait OrigResponse
{
    protected $origResponse = '';

    /**
     * 获取原始的响应结果
     * @return string
     */
    public function getOrigResponse()
    {
        return $this->origResponse;
    }

}
