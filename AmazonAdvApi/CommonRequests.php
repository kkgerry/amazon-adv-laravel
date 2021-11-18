<?php

namespace AmazonAdvApi;

/**
 * Trait Common Resource
 * Contains requests' wrappers of Amazon Ads API for Common Resource
 */
trait CommonRequests
{
    /**
     * @param null|array $data
     * @return array
     */
    public function getInvoiceList($data = null)
    {

        return $this->commonRequest("invoices",$data);
    }

    /**
     * @param $invoiceId
     * @return array
     */
    public function getInvoice($invoiceId)
    {
        return $this->commonRequest("/invoices/{$invoiceId}");
    }

}
