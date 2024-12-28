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
     * 受众细分列表
     * @User Gerry
     * @Time 2021-11-30 18:14
     * @param array $data
     * @return mixed
     */
    public function getAudiencesList(array $data=[])
    {
        return $this->commonRequest('audiences/list',$data,'POST');
    }
    public function getHistory(array $data)
    {
        return $this->commonRequest('history',$data,'POST');
    }

    /**
     * 创建上传资源
     * @User Luc
     * @Time 2024-10-18 16:14
     * @param array $data
     * @return mixed
     */
    public function getAssetsUploadLocation(array $data=[])
    {
        return $this->commonRequest('assets/upload',$data,'POST');
    }

}
