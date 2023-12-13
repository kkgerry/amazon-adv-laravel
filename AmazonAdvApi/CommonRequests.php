<?php

namespace AmazonAdvApi;

/**
 * Trait Common Resource
 * Contains requests' wrappers of Amazon Ads API for Common Resource
 */
trait CommonRequests
{
    /**
     * 广告发票列表
     * @param null|array $data
     * @return array
     */
    public function getInvoiceList($data = null)
    {

        return $this->commonRequest("invoices",$data);
    }

    /**
     * 广告发票详细
     * @param $invoiceId
     * @return array
     */
    public function getInvoice($invoiceId)
    {
        return $this->commonRequest("/invoices/{$invoiceId}");
    }

    /**
     * 按条件返回可选产品
     * @User Gerry
     * @Time 2021-11-30 18:14
     * @param array $data
     * @return mixed
     */
    public function getProductSelector(array $data=[])
    {
        return $this->commonRequest('product/metadata',$data,'POST');
    }

    /**
     * 获取数据调整记录
     * @User Gerry
     * @Time 2023-12-13 15:42
     * @param array $data
     * @return mixed
     */
    public function getHistoryList(array $data)
    {
        return $this->commonRequest('history',$data,'POST');
    }

}
