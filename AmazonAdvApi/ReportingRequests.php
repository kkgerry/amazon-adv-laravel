<?php

namespace AmazonAdvApi;

use Exception;

/**
 * Trait Report
 * Contains requests' wrappers of Amazon Ads API for Sponsored Products
 */
trait ReportingRequests
{

    protected function setContentType(&$data)
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'application/vnd.createasyncreportrequest.v3+json';
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function requestReporting($data = null)
    {
        $this->setContentType($data);
        return $this->operation( "reporting/reports", $data, "POST");
    }

    /**
     * @param $reportId
     * @return array
     * @throws Exception
     */
    public function getReporting($reportId)
    {
        $this->setContentType($data);
        $req = $this->operation("reporting/reports/{$reportId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESS") {
                return $this->download($json["location"]);
            }
        }
        return $req;
    }
}
