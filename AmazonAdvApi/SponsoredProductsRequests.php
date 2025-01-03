<?php

namespace AmazonAdvApi;

use Exception;

/**
 * Trait SponsoredProductsRequests
 * Contains requests' wrappers of Amazon Ads API for Sponsored Products
 */
trait SponsoredProductsRequests
{
    /**
     * @param $campaignId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getCampaign($campaignId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }
        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }

        return $this->operation($type . "campaigns/{$campaignId}");
    }

    /**
     * @param $campaignId
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function getCampaignEx($campaignId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }
        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaigns/extended/{$campaignId}");
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function createCampaigns($data=[])
    {
//        $type = $this->campaignTypePrefix ?: 'sp';
//        if ($this->apiVersion == 'v1') {
//            $type = null;
//        } else {
//            $type = $type . "/";
//        }
//        if (!$type && $this->apiVersion == 'v2') {
//            $this->logAndThrow("Unable to perform request. No type is set");
//        }
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spcampaign.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spCampaign.v3+json';

        return $this->operation( "sp/campaigns", $data, "POST");
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function updateCampaigns($data=[])
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }
        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaigns", $data, "PUT");
    }

    /**
     * @param $campaignId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function archiveCampaign($campaignId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }
        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaigns/{$campaignId}", null, "DELETE");
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listCampaigns(?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }
        if (isset($data['campaignType']) && $type === 'hsa/') {
            unset($data['campaignType']);
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaigns", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listCampaignsEx(?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaigns/extended", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listSpCampaignsExV3(?array $data = null)
    {
        $this->setEndpoints('v3');
        $data['includeExtendedDataFields'] = true;
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spCampaign.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spCampaign.v3+json';
        return $this->operation( "sp/campaigns/list", $data,'POST');
    }
    /**
     * @param $adGroupId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getAdGroup($adGroupId, ?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "adGroups/{$adGroupId}");
    }

    /**
     * @param $adGroupId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getAdGroupEx($adGroupId, ?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "adGroups/extended/{$adGroupId}");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createAdGroups($data=[])
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spAdGroup.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spAdGroup.v3+json';

        return $this->operation( "sp/adGroups", $data, "POST");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateAdGroups($data=[])
    {

        return $this->operation("sp/adGroups", $data, "PUT");
    }

    /**
     * @param $adGroupId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function archiveAdGroup($adGroupId, ?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "adGroups/{$adGroupId}", null, "DELETE");
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listAdGroups(?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "adGroups", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listAdGroupsEx(?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "adGroups/extended", $data);
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getBiddableKeyword($keywordId)
    {
        return $this->operation("sp/keywords/{$keywordId}");
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getBiddableKeywordEx($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "keywords/extended/{$keywordId}");
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function createBiddableKeywords(array $data)
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spKeyword.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spKeyword.v3+json';

        return $this->operation( "sp/keywords", $data, "POST");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateBiddableKeywords(array $data)
    {

        return $this->operation( "sp/keywords", $data, "PUT");
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function archiveBiddableKeyword($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "keywords/{$keywordId}", null, "DELETE");
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listBiddableKeywords(?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "keywords", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listBiddableKeywordsEx(?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "keywords/extended", $data);
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getNegativeKeyword($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "negativeKeywords/{$keywordId}");
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getNegativeKeywordEx($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "negativeKeywords/extended/{$keywordId}");
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function createNegativeKeywords(array $data)
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spNegativeKeyword.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spNegativeKeyword.v3+json';

        return $this->operation( "sp/negativeKeywords", $data, "POST");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateNegativeKeywords(array $data)
    {
        return $this->operation( "sp/negativeKeywords", $data, "PUT");
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function archiveNegativeKeyword($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "negativeKeywords/{$keywordId}", null, "DELETE");
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listNegativeKeywords(?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "negativeKeywords", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listNegativeKeywordsEx(?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "negativeKeywords/extended", $data);
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getCampaignNegativeKeyword($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaignNegativeKeywords/{$keywordId}");
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getCampaignNegativeKeywordEx($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaignNegativeKeywords/extended/{$keywordId}");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCampaignNegativeKeywords(array $data)
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spNegativeKeyword.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spNegativeKeyword.v3+json';

        return $this->operation( "sp/campaignNegativeKeywords", $data, "POST");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateCampaignNegativeKeywords(array $data)
    {
        return $this->operation( "sp/campaignNegativeKeywords", $data, "PUT");
    }

    /**
     * @param $keywordId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function removeCampaignNegativeKeyword($keywordId, ?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaignNegativeKeywords/{$keywordId}", null, "DELETE");
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listCampaignNegativeKeywords(?array $data = null)
    {
        return $this->operation("sp/campaignNegativeKeywords", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listCampaignNegativeKeywordsEx(?array $data = null)
    {
        $type = $this->campaignTypePrefix ?: 'sp';
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "campaignNegativeKeywords/extended", $data);
    }

    /**
     * @param $productAdId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getProductAd($productAdId, ?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "productAds/{$productAdId}");
    }

    /**
     * @param $productAdId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function getProductAdEx($productAdId, ?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation("sp/productAds/extended/{$productAdId}");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createProductAds(array $data)
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spProductAd.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spProductAd.v3+json';

        return $this->operation( "sp/productAds", $data, "POST");
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateProductAds(array $data)
    {
        return $this->operation( "sp/productAds", $data, "PUT");
    }

    /**
     * @param $productAdId
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function archiveProductAd($productAdId, ?array $data = null)
    {
        $type = $this->getCampaignTypeFromData($data);
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            if (isset($data['campaignType'])) {
                unset($data['campaignType']);
            }
            $type = $type . "/";
        }

        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "productAds/{$productAdId}", null, "DELETE");
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listProductAds(?array $data = null)
    {
        $this->setEndpoints('v3');
        $data['includeExtendedDataFields'] = true;
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spProductAd.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spProductAd.v3+json';
        return $this->operation( "sp/productAds/list", $data,'POST');

    }

    /**
     * 弃用通知：此端点将于 2022 年 12 月 31 日弃用。今后使用基于主题的出价建议。
     * @param $adGroupId
     * @return array
     * @throws Exception
     */
    public function getAdGroupBidRecommendations($adGroupId)
    {
        $params['_headers']['adGroupId'] = $adGroupId;
        return $this->operation("sp/adGroups/{$adGroupId}/bidRecommendations");
    }

    /**
     * 弃用通知：此端点将于 2022 年 12 月 31 日弃用。今后使用基于主题的出价建议。
     * @param $keywordId
     * @return array
     * @throws Exception
     */
    public function getKeywordBidRecommendations($keywordId)
    {
        return $this->operation("sp/keywords/{$keywordId}/bidRecommendations");
    }

    /**
     * 弃用通知：此端点将于 2022 年 12 月 31 日弃用。今后使用基于主题的出价建议。
     * @param $adGroupId
     * @param $data
     * @return array
     * @throws Exception
     */
    public function bulkGetKeywordBidRecommendations(array $data)
    {
        return $this->operation("sp/keywords/bidRecommendations", $data, "POST");
    }

    /**
     * 广告组或ASIN 基于主题的报价推荐
     * @User Gerry
     * @Time 2022-8-26 14:58
     * @param array $data
     * @return mixed
     */
    public function getThemeBasedBidRecommendation(array $data)
    {
        $this->setEndpoints('v3');
        return $this->operation("sp/targets/bid/recommendations", $data, "POST");
    }
    /**
     * @param $adGroupId
     * @param $data
     * @return array
     * @throws Exception
     */
    public function bulkGetTargetingBidRecommendations(array $data)
    {
        return $this->operation("sp/targets/bidRecommendations", $data, "POST");
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function getAdGroupKeywordSuggestions($data)
    {
        $adGroupId = $data["adGroupId"];
        unset($data["adGroupId"]);
        return $this->operation("sp/adGroups/{$adGroupId}/suggested/keywords", $data);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getAdGroupKeywordSuggestionsEx(array $data)
    {
        $adGroupId = $data["adGroupId"];
        unset($data["adGroupId"]);
        return $this->operation("sp/adGroups/{$adGroupId}/suggested/keywords/extended", $data);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getAsinKeywordSuggestions(array $data)
    {
        $asin = $data["asin"];
        unset($data["asin"]);
        return $this->operation("sp/asins/{$asin}/suggested/keywords", $data);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function bulkGetAsinKeywordSuggestions(array $data)
    {
        return $this->operation("sp/asins/suggested/keywords", $data, "POST");
    }

    /**
     * GET /v2/stores
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function getStores(?array $data = null)
    {
        return $this->operation("stores", $data);
    }

    /**
     * GET /v2stores/{$brandEntityId}
     * @param int $brandEntityId
     * @return array
     * @throws Exception
     */
    public function getStoresByBrandEntityId($brandEntityId)
    {
        return $this->operation("stores/{$brandEntityId}");
    }

    /**
     * @param $recordType
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function requestSnapshot($recordType,$adClass='sp', ?array $data = null)
    {
        if($adClass == 'sd'){
            $this->setEndpoints('v3');
        }
        return $this->operation("{$adClass}/{$recordType}/snapshot", $data, "POST");
    }

    /**
     * @param $snapshotId
     * @return array
     * @throws Exception
     */
    public function getSnapshot($snapshotId,$adClass='sp')
    {
        if($adClass == 'sd'){
            $this->setEndpoints('v3');
        }
        $req = $this->operation("{$adClass}/snapshots/{$snapshotId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESS") {
                return $this->download($json["location"]);
            }
        }
        return $req;
    }

    /**
     * @param $recordType
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function requestReport($recordType, ?array $data = null)
    {
        $type = $this->getCampaignTypeForReportRequest($data);
        if($type == 'sd'){
            $this->setEndpoints('v3');
        }
        if ($this->apiVersion == 'v1') {
            $type = null;
        } else {
            $type = $type . "/";
            if (is_array($data) && isset($data['reportType'])) {
                unset($data['reportType']);
            }
        }
        if (!$type && $this->apiVersion == 'v2') {
            $this->logAndThrow("Unable to perform request. No type is set");
        }
        return $this->operation($type . "{$recordType}/report", $data, "POST");
    }

    /**
     * @param array|null $data
     * @return string
     * @throws Exception
     */
    private function getCampaignTypeForReportRequest(?array $data): string
    {
        $reportType = is_array($data) && isset($data['reportType'])
            ? $data['reportType']
            : 'sponsoredProducts';
        if ($reportType === 'sponsoredProducts') {
            return 'sp';
        } elseif ($reportType === 'sponsoredBrands') {
            return 'hsa';
        } elseif ($reportType === 'sponsoredDisplay') {
            return 'sd';
        } else {
            throw new Exception("Invalid reportType $reportType");
        }
    }

    /**
     * @param $reportId
     * @return array
     * @throws Exception
     */
    public function getReport($reportId)
    {
        $req = $this->operation("reports/{$reportId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESS") {
                return $this->download($json["location"]);
            }
        }
        return $req;
    }

    //portfolios part

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listPortfolios(?array $data = null)
    {
        return $this->operation("portfolios", $data);
    }

    /**
     * @param null|array $data
     * @return array
     * @throws Exception
     */
    public function listPortfoliosEx(?array $data = null)
    {
        return $this->operation("portfolios/extended", $data);
    }

    /**
     * @param int $portfolioId
     * @return array
     * @throws Exception
     */
    public function getPortfolio(int $portfolioId)
    {
        return $this->operation('portfolios/' . $portfolioId);
    }

    /**
     * @param int $portfolioId
     * @return array
     * @throws Exception
     */
    public function getPortfolioEx(int $portfolioId)
    {
        return $this->operation('portfolios/extended/' . $portfolioId);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createPortfolios(array $data)
    {
        return $this->operation('portfolios', $data, 'POST');
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updatePortfolios(array $data)
    {
        return $this->operation('portfolios', $data, 'PUT');
    }

    //start of Product Attribute Targeting

    /**
     * POST https://advertising-api.amazon.com/v2/sp/targets/productRecommendations
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#createTargetRecommendations
     *
     * @param array $data [pageSize => int(1-50), pageNumber => int, asins: string[]]
     * @return array
     * @throws Exception
     */
    public function generateTargetsProductRecommendations(array $data): array
    {
        $this->setEndpoints();
        //$data['_headers']['content_type'] = 'Content-Type: application/vnd.spproducttargeting.v3+json';
        return $this->operation("sp/targets/productRecommendations", $data, 'POST');
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets/{targetId}
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getTargetingClause
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function getTargetingClause(int $targetId): array
    {
        return $this->operation("sp/targets/" . $targetId);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets/extended/{targetId}
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getTargetingClauseEx
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function getTargetingClauseEx(int $targetId): array
    {
        return $this->operation("sp/targets/extended/" . $targetId);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#listTargetingClauses
     *
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function listTargetingClauses(?array $data = null): array
    {
        return $this->operation("sp/targets", $data);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets/extended
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#listTargetingClausesEx
     *
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function listTargetingClausesEx(?array $data = null): array
    {
        return $this->operation("sp/targets/extended", $data);
    }

    /**
     * POST https://advertising-api.amazon.com/v2/sp/targets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#createTargetingClauses
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createTargetingClauses(array $data): array
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spTargetingClause.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spTargetingClause.v3+json';

        return $this->operation("sp/targets", $data, 'POST');
    }

    /**
     * PUT https://advertising-api.amazon.com/v2/sp/targets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#updateTargetingClauses
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateTargetingClauses(array $data): array
    {
        return $this->operation("sp/targets", $data, 'PUT');
    }

    /**
     * DELETE https://advertising-api.amazon.com/v2/sp/targets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#archiveTargetingClause
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function archiveTargetingClause(int $targetId): array
    {
        return $this->operation("sp/targets/" . $targetId, 'DELETE');
    }


    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets/categories
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getTargetingCategories
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getTargetingCategories(array $data): array
    {
        return $this->operation("sp/targets/categories", $data);
    }
    public function getTargetingRecommendCategories(array $data): array
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spproducttargeting.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spproducttargeting.v3+json';
        return $this->operation("sp/targets/categories/recommendations", $data,'POST');
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets/brands
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getBrandRecommendations
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getBrandRecommendations(array $data): array
    {
        return $this->operation("sp/targets/brands", $data);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/targets/{targetId}
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getNegativeTargetingClause
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function getNegativeTargetingClause(int $targetId): array
    {
        return $this->operation("sp/negativeTargets/" . $targetId);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/negativeTargets/extended/{targetId}
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getNegativeTargetingClauseEx
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function getNegativeTargetingClauseEx(int $targetId): array
    {
        return $this->operation("sp/negativeTargets/extended/" . $targetId);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/negativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#listNegativeTargetingClauses
     *
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function listNegativeTargetingClauses(?array $data = null): array
    {
        return $this->operation("sp/negativeTargets", $data);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/negativeTargets/extended
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#listNegativeTargetingClausesEx
     *
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function listNegativeTargetingClausesEx(?array $data = null): array
    {
        return $this->operation("sp/negativeTargets/extended", $data);
    }

    //

    /**
     * POST https://advertising-api.amazon.com/v2/sp/negativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#createNegativeTargetingClauses
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createNegativeTargetingClauses(array $data): array
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spNegativeTargetingClause.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spNegativeTargetingClause.v3+json';

        return $this->operation("sp/negativeTargets", $data, 'POST');
    }

    /**
     * PUT https://advertising-api.amazon.com/v2/sp/negativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#updateNegativeTargetingClauses
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateNegativeTargetingClauses(array $data): array
    {
        return $this->operation("sp/negativeTargets", $data, 'PUT');
    }

    /**
     * DELETE https://advertising-api.amazon.com/v2/sp/negativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#archiveNegativeTargetingClause
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function archiveNegativeTargetingClause(int $targetId): array
    {
        return $this->operation("sp/negativeTargets/" . $targetId, 'DELETE');
    }

    //campaign negative products

    /**
     * GET https://advertising-api.amazon.com/v2/sp/campaignNegatuveTargets/{targetId}
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getNegativeTargetingClause
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function getCampaignNegativeTargetingClause(int $targetId): array
    {
        return $this->operation("sp/campaignNegativeTargets/" . $targetId);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/campaignNegativeTargets/extended/{targetId}
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#getNegativeTargetingClauseEx
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function getCampaignNegativeTargetingClauseEx(int $targetId): array
    {
        return $this->operation("sp/campaignNegativeTargets/extended/" . $targetId);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/campaignNegativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#listNegativeTargetingClauses
     *
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function listCampaignNegativeTargetingClauses(?array $data = null): array
    {
        return $this->operation("sp/campaignNegativeTargets", $data);
    }

    /**
     * GET https://advertising-api.amazon.com/v2/sp/campaignNegativeTargets/extended
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#listNegativeTargetingClausesEx
     *
     * @param array|null $data
     * @return array
     * @throws Exception
     */
    public function listCampaignNegativeTargetingClausesEx(?array $data = null): array
    {
        return $this->operation("sp/campaignNegativeTargets/extended", $data);
    }

    //

    /**
     * POST https://advertising-api.amazon.com/v2/sp/campaignNegativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#createNegativeTargetingClauses
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCampaignNegativeTargetingClauses(array $data): array
    {
        return $this->operation("sp/campaignNegativeTargets", $data, 'POST');
    }

    /**
     * PUT https://advertising-api.amazon.com/v2/sp/campaignNegativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#updateNegativeTargetingClauses
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateCampaignNegativeTargetingClauses(array $data): array
    {
        return $this->operation("sp/campaignNegativeTargets", $data, 'PUT');
    }

    /**
     * DELETE https://advertising-api.amazon.com/v2/sp/campaignNegativeTargets
     * @see https://advertising.amazon.com/API/docs/v2/reference/product_attribute_targeting#archiveNegativeTargetingClause
     *
     * @param int $targetId
     * @return array
     * @throws Exception
     */
    public function archiveCampaignNegativeTargetingClause(int $targetId): array
    {
        return $this->operation("sp/campaignNegativeTargets/" . $targetId, 'DELETE');
    }


    /**
     * POST https://advertising-api.amazon.com/sp/global/targets/keywords/recommendations/list
     * @see https://advertising.amazon.com/API/docs/en-us/sponsored-products/3-0/openapi/prod#tag/Keyword-Targets/operation/getRankedKeywordRecommendation
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getGlobalKeywordRecommendations(array $data)
    {
        return $this->operation("/sp/global/targets/keywords/recommendations/list", $data, "POST");
    }

    /**
     * POST https://advertising-api.amazon.com/sp/global/targets/keywords/recommendations/list
     * @see https://advertising.amazon.com/API/docs/en-us/sponsored-products/3-0/openapi/prod#tag/Keyword-Targets/operation/getRankedKeywordRecommendation
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getKeywordRecommendations(array $data)
    {
        $this->setEndpoints('v5');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spkeywordsrecommendation.v5+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spkeywordsrecommendation.v5+json';

        return $this->operation("sp/targets/keywords/recommendations", $data, "POST");
    }

    public function getTargetsBidRecommendations(array $data)
    {
        return $this->operation("sp/targets/bidRecommendations", $data, "POST");
    }

    public function getTargetingRecommendCategoriesV5(array $data): array
    {
        $this->setEndpoints('v5');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spproducttargeting.v5+json';
        return $this->operation("sp/targets/categories/recommendations", $data,'POST');
    }

    public function getRecommendTargetAsin(array $data): array
    {
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spproductrecommendation.asin.v3+json';
        return $this->operation("sp/targets/products/recommendations", $data,'POST');
    }

    public function getTargetProductCount(array $data)
    {
        return $this->operation("sp/targets/products/count", $data, "POST");
    }

    public function getNegativeTargetRecommendationsBrands(array $data)
    {
        $this->setEndpoints('v3');
        $data['_headers']['content_type'] = 'Content-Type: application/vnd.spproducttargetingresponse.v3+json';
        $data['_headers']['accept'] = 'Accept: application/vnd.spproducttargetingresponse.v3+json';
        return $this->operation("sp/negativeTargets/brands/recommendations", $data, "GET");
    }
}
