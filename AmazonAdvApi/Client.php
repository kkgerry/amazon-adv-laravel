<?php

namespace AmazonAdvApi;

use Exception;

/*require_once "CurlRequest.php";
require_once "CommonRequests.php";
require_once "SponsoredProductsRequests.php";
require_once "SponsoredBrandsRequests.php";
require_once "SponsoredDisplayRequests.php";
require_once "ProductEligibilityRequests.php";
require_once "ProfileRequests.php";*/

/**
 * Class Client
 * Contains requests' wrappers of Amazon Ads API
 */
class Client
{
    use SponsoredProductsRequests;
    use SponsoredBrandsRequests;
    use SponsoredDisplayRequests;
    use ReportingRequests;
    use ProductEligibilityRequests;
    use ProfileRequests;
    use CommonRequests;

    public const CAMPAIGN_TYPE_SPONSORED_PRODUCTS = 'sponsoredProducts';
    public const CAMPAIGN_TYPE_SPONSORED_BRANDS = 'sponsoredBrands';
    public const CAMPAIGN_TYPE_SPONSORED_DISPLAY = 'sponsoredDisplay';

    private $config = [
        'clientId' => null,
        'clientSecret' => null,
        'region' => null,
        'accessToken' => null,
        'refreshToken' => null,
        'sandbox' => false,
        'saveFile' => false,
        'apiVersion' => 'v1',
        'deleteGzipFile' => false,
    ];

    private $apiVersion = null;
    private $applicationVersion = null;
    private $userAgent = null;
    private $endpoint = null;
    private $commonUrl = null;
    private $tokenUrl = null;
    private $requestId = null;
    private $endpoints = [
        "na" => [
            "prod"     => "advertising-api.amazon.com",
            "sandbox"  => "advertising-api-test.amazon.com",
            "tokenUrl" => "api.amazon.com/auth/o2/token"
        ],
        "eu" => [
            "prod"     => "advertising-api-eu.amazon.com",
            "sandbox"  => "advertising-api-test.amazon.com",
            "tokenUrl" => "api.amazon.com/auth/o2/token"
        ],
        "fe" => [
            "prod"     => "advertising-api-fe.amazon.com",
            "sandbox"  => "advertising-api-test.amazon.com",
            "tokenUrl" => "api.amazon.com/auth/o2/token"
        ]
    ];
    public $campaignTypePrefix;

    public $profileId = null;

    public $headers = [];
    private $versionStrings = array(
        "apiVersion"         => "v1",
        "applicationVersion" => "1.0"
    );
    /**
     * Client constructor.
     * @param $config
     * @throws Exception
     */
    public function __construct($config)
    {
        $this->apiVersion = $config['apiVersion'] ?? null;

        $this->apiVersion = is_null($this->apiVersion) ? $this->versionStrings["apiVersion"] : $this->apiVersion;
        $this->applicationVersion = $this->versionStrings["applicationVersion"];
        $this->userAgent = "AdvAPI PHP Client Library v{$this->applicationVersion}";

        $this->validateConfig($config);
        $this->validateConfigParameters();
        $this->setEndpoints($this->apiVersion);

        if (is_null($this->config["accessToken"]) && !is_null($this->config["refreshToken"])) {
            /* convenience */
            $this->doRefreshToken();
        }
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        if (isset($this->{$name})) {
            $this->{$name} = $value;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function doRefreshToken()
    {
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
            "User-Agent: {$this->userAgent}"
        );

        $refresh_token = rawurldecode($this->config["refreshToken"]);

        $params = array(
            "grant_type" => "refresh_token",
            "refresh_token" => $refresh_token,
            "client_id" => $this->config["clientId"],
            "client_secret" => $this->config["clientSecret"]
        );

        $data = "";
        foreach ($params as $k => $v) {
            $data .= "{$k}=" . rawurlencode($v) . "&";
        }

        $url = "https://{$this->tokenUrl}";

        $request = new CurlRequest();
        $request->setOption(CURLOPT_URL, $url);
        $request->setOption(CURLOPT_HTTPHEADER, $headers);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        $request->setOption(CURLOPT_POST, true);
        $request->setOption(CURLOPT_POSTFIELDS, rtrim($data, "&"));

        $response = $this->executeRequest($request);

        $response_array = json_decode($response["response"], true);
        if (is_array($response_array) && array_key_exists("access_token", $response_array)) {
            $this->config["accessToken"] = $response_array["access_token"];
        } else {
            $this->logAndThrow("Unable to refresh token. 'access_token' not found in response. " . print_r($response,
                    true));
        }

        return $response;
    }

    /**
     * @param $location
     * @param bool $gunzip
     * @return array
     */
    private function download($location, $gunzip = false)
    {
        $headers = array();

        if (!$gunzip) {
            /* only send authorization header when not downloading actual file */
            array_push($headers, "Authorization: bearer {$this->config["accessToken"]}");
        }

        if (!is_null($this->profileId)) {
            array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileId}");

            if(stripos($location,'sd/snapshots')) {
                array_push($headers, "Amazon-Advertising-API-ClientId: {$this->config["clientId"]}");
            }
        }

        $request = new CurlRequest();
        $request->setOption(CURLOPT_URL, $location);
        $request->setOption(CURLOPT_HTTPHEADER, $headers);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        if ($this->config['saveFile'] && $gunzip) {
            return $this->saveDownloaded($request);
        }

        if ($gunzip) {
            $response = $this->executeRequest($request);
            try{
                $response["response"] = gzdecode($response["response"]);
            }catch (\Exception $e){
                \Log::error('拉取广告数据解压失败：'.$e->getMessage());
                $response["response"] = $response["response"];
            }

            return $response;
        }

        return $this->executeRequest($request);
    }

    /**
     * Save *.json.gz file, extract it, remove .gz file
     * and set into response path to json file
     * @param CurlRequest $request
     * @return array
     */
    protected function saveDownloaded(CurlRequest $request): array
    {
        $filePath = '/tmp/' . uniqid(microtime(true) . '_amzn_ads_') . '.json.gz';
        $tmpFile = fopen($filePath, 'w+');
        $request->setOption(CURLOPT_HEADER, 0);
        $request->setOption(CURLOPT_FOLLOWLOCATION, 1);
        $request->setOption(CURLOPT_FILE, $tmpFile);
        $response = $this->executeRequest($request);
        if ($response['success']) {
            $extractedFile = $this->extractFile($filePath);
            fclose($tmpFile);
            $response['response_type'] = 'file';
            $response["response"] = $extractedFile;
            return $response;
        } else {
            fclose($tmpFile);
            unlink($filePath);
            return $response;
        }
    }

    /**
     * @param string $filePath
     * @return string
     */
    protected function extractFile(string $filePath): string
    {
        $bufferSize = 4096; // read 4kb at a time
        $unzipFilePath = str_replace('.gz', '', $filePath);
        $file = gzopen($filePath, 'rb');
        $unzippedFile = fopen($unzipFilePath, 'wb');

        while (!gzeof($file)) {
            fwrite($unzippedFile, gzread($file, $bufferSize));
        }
        fclose($unzippedFile);
        gzclose($file);

        return $unzipFilePath;
    }

    /**
     * @param string $interface
     * @param array|null $params
     * @param string $method
     * @return array
     * @throws Exception
     */
    private function operation(string $interface, $params = null, string $method = "GET")
    {
        //print_r($params);
        $headers = array(
            'authorization' => "Authorization: bearer {$this->config["accessToken"]}",
            'content_type' => "Content-Type: application/json",
            'user_agent' => "User-Agent: {$this->userAgent}",
            'amazon_adv_api_client_id' => "Amazon-Advertising-API-ClientId: {$this->config["clientId"]}"
        );

        if(isset($params['_headers'])){
            $headers = array_merge($headers,$params['_headers']);
            unset($params['_headers']);
        }

        if (!is_null($this->profileId)) {
            $headers['amazon_adv_api_scope'] = "Amazon-Advertising-API-Scope: {$this->profileId}";
            //array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileId}");
        }
        if(isset($params['headers']['accept']) && !empty($params['headers']['accept'])){
            $headers['accept'] = $params['headers']['accept'];
        }
        unset($params['headers']);

        $headers = array_values($headers);

        //print_r($headers);
        //die();
        $this->headers = $headers;

        $request = new CurlRequest();
        $this->endpoint = trim($this->endpoint, "/");
        $url = "{$this->endpoint}/{$interface}";
        $this->requestId = null;
        //echo $url; //die();
        switch (strtolower($method)) {
            case "get":
                if (!empty($params)) {
                    $url .= "?";
                    foreach ($params as $k => $v) {
                        $url .= "{$k}=" . rawurlencode($v) . "&";
                    }
                    $url = rtrim($url, "&");
                }
                break;
            case "put":
            case "post":
            case "delete":
                if (!empty($params)) {
                    if(!isset($params['multipart_form_data'])) {
                        if (is_array($params)) {
                            $data = json_encode($params);
                        } else {
                            $data = $params;
                        }
                    }else{
                        unset($params['multipart_form_data']);
                        $data = $params;
                    }
                    //print_r($data);die();
                    $request->setOption(CURLOPT_POST, true);
                    $request->setOption(CURLOPT_POSTFIELDS, $data);
                }
                break;

            default:
                $this->logAndThrow("Unknown verb {$method}.");
        }

        $request->setOption(CURLOPT_URL, $url);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        $request->setOption(CURLOPT_CUSTOMREQUEST, strtoupper($method));
        $request->setOption(CURLOPT_HTTPHEADER, $headers);

        $this->logSave('广告API对接',[$url,$params]);
        return $this->executeRequest($request);
    }
    /**
     * @param string $interface
     * @param array|null $params
     * @param string $method
     * @return array
     * @throws Exception
     */
    private function commonRequest(string $interface, ?array $params = [], string $method = "GET")
    {
        $headers = array(
            "Authorization: bearer {$this->config["accessToken"]}",
            "Content-Type: application/json",
            "User-Agent: {$this->userAgent}",
            "Amazon-Advertising-API-ClientId: {$this->config["clientId"]}"
        );

        if (!is_null($this->profileId)) {
            array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileId}");
        }

        $this->headers = $headers;

        $request = new CurlRequest();
        $this->commonUrl = trim($this->commonUrl, "/");

        $url = "{$this->commonUrl}/{$interface}";
        $this->requestId = null;

        switch (strtolower($method)) {
            case "get":
                if (!empty($params)) {
                    $url .= "?";
                    foreach ($params as $k => $v) {
                        $url .= "{$k}=" . rawurlencode($v) . "&";
                    }
                    $url = rtrim($url, "&");
                }
                break;
            case "put":
            case "post":
            case "delete":
                if (!empty($params)) {
                    $data = json_encode($params);
                    $request->setOption(CURLOPT_POST, true);
                    $request->setOption(CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                $this->logAndThrow("Unknown verb {$method}.");
        }

        $request->setOption(CURLOPT_URL, $url);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        $request->setOption(CURLOPT_CUSTOMREQUEST, strtoupper($method));
        $request->setOption(CURLOPT_HTTPHEADER, $headers);

        $this->logSave('广告公共API对接',[$url,$params]);
        return $this->executeRequest($request);
    }

    /**
     * @param CurlRequest $request
     * @return array
     */
    protected function executeRequest(CurlRequest $request)
    {
        $this->logSave('AmzAdv Request Start');
        $requestInfo = $request->getInfo();
        $response = $request->execute();
        $this->logSave('AmzAdv Request End');
        $this->requestId = $request->requestId;
        $response_info = $request->getInfo();
        $request->close();

        if ($response_info["http_code"] == 307) {
            /* application/octet-stream */
            return $this->download($response_info["redirect_url"], true);
        }

        if (!preg_match("/^(2|3)\d{2}$/", $response_info["http_code"])) {
            $requestId = 0;
            $json = json_decode($response, true);
            if (!is_null($json)) {
                if (array_key_exists("requestId", $json)) {
                    $requestId = json_decode($response, true)["requestId"];
                }
            }
            return array(
                "success" => false,
                "code" => $response_info["http_code"],
                "response" => $response,
                'responseInfo' => $response_info,
                'requestInfo' => $requestInfo,
                "requestId" => $requestId
            );
        } else {
            return array(
                "success" => true,
                "code" => $response_info["http_code"],
                'responseInfo' => $response_info,
                'requestInfo' => $requestInfo,
                "response" => $response,
                "requestId" => $this->requestId
            );
        }
    }

    /**
     * @param $config
     * @return bool
     * @throws Exception
     */
    private function validateConfig($config)
    {
        if (is_null($config)) {
            $this->logAndThrow("'config' cannot be null.");
        }

        foreach ($config as $k => $v) {
            if (array_key_exists($k, $this->config)) {
                $this->config[$k] = $v;
            } else {
                $this->logAndThrow("Unknown parameter '{$k}' in config.");
            }
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function validateConfigParameters()
    {
        foreach ($this->config as $k => $v) {
            if (is_null($v) && $k !== "accessToken" && $k !== "refreshToken") {
                $this->logAndThrow("Missing required parameter '{$k}'.");
            }
            switch ($k) {
                case "clientId":
                    if (!preg_match("/^amzn1\.application-oa2-client\.[a-z0-9]{32}$/i", $v)) {
                        $this->logAndThrow("Invalid parameter value for clientId.");
                    }
                    break;
                case "clientSecret":
                    if (!preg_match("/^[a-z0-9]{64}$/i", $v)) {
                        $this->logAndThrow("Invalid parameter value for clientSecret.");
                    }
                    break;
                case "accessToken":
                    if (!is_null($v)) {
                        if (!preg_match("/^Atza(\||%7C|%7c).*$/", $v)) {
                            $this->logAndThrow("Invalid parameter value for accessToken.");
                        }
                    }
                    break;
                case "refreshToken":
                    if (!is_null($v)) {
                        if (!preg_match("/^Atzr(\||%7C|%7c).*$/", $v)) {
                            $this->logAndThrow("Invalid parameter value for refreshToken.");
                        }
                    }
                    break;
                case "sandbox":
                    if (!is_bool($v)) {
                        $this->logAndThrow("Invalid parameter value for sandbox.");
                    }
                    break;
                case "saveFile":
                    if (!is_bool($v)) {
                        $this->logAndThrow("Invalid parameter value for saveFile.");
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function setEndpoints($t='')
    {
        /* check if region exists and set api/token endpoints */
        if (array_key_exists(strtolower($this->config["region"]), $this->endpoints)) {
            $region_code = strtolower($this->config["region"]);
            if ($this->config["sandbox"]) {
                if($t == 'v3' || $t == 'v4' || $t == 'v5'){
                    $this->endpoint = "https://{$this->endpoints[$region_code]["sandbox"]}/";
                }else {
                    $this->endpoint = "https://{$this->endpoints[$region_code]["sandbox"]}/{$this->apiVersion}";
                    $this->commonUrl = "https://{$this->endpoints[$region_code]["sandbox"]}";
                }
            } else {
                if($t == 'v3' || $t == 'v4' || $t == 'v5'){
                    $this->endpoint = "https://{$this->endpoints[$region_code]["prod"]}/";
                }else {
                    $this->endpoint = "https://{$this->endpoints[$region_code]["prod"]}/{$this->apiVersion}";
                    $this->commonUrl = "https://{$this->endpoints[$region_code]["prod"]}";
                }
            }

            $this->tokenUrl = $this->endpoints[$region_code]["tokenUrl"];

            if($this->apiVersion != $t) $this->apiVersion = $t;

        } else {
            $this->logAndThrow("Invalid region.");
        }
        return true;
    }


    /**
     * @param $message
     * @throws Exception
     */
    private function logAndThrow($message)
    {
        throw new Exception($message);
    }
    /**
     * @param array|null $data
     * @return string
     */
    protected function getCampaignTypeFromData(?array $data): string
    {
        if (empty($data)) {
            $campaignType = static::CAMPAIGN_TYPE_SPONSORED_PRODUCTS;
        }else {
            $campaignType = is_array($data) && isset($data['campaignType'])
                ? $data['campaignType']
                : static::CAMPAIGN_TYPE_SPONSORED_PRODUCTS;
        }
        if ($campaignType === static::CAMPAIGN_TYPE_SPONSORED_PRODUCTS) {
            return 'sp';
        } elseif ($campaignType === static::CAMPAIGN_TYPE_SPONSORED_BRANDS) {
            return 'hsa';
        } elseif ($campaignType === static::CAMPAIGN_TYPE_SPONSORED_DISPLAY) {
            return 'sd';
        } else {
            return 'sp';
        }
    }

    public function logSave($title = '', $data = [])
    {
        if (!empty($title) || !empty($data)) {
            $type = 'amazon_adv/';
            $arrType = explode('/', $type);

            if (count($arrType) > 1) {
                unset($arrType[count($arrType) - 1]);
                $path = storage_path('logs') . '/' . implode('/', $arrType);
                if (!is_dir($path)) {
                    //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                    @mkdir($path, 0777, true);
                }
            }

            $path = storage_path('logs') . '/' . $type . date("Y-m-d") . '.log';
            $content = date('Y-m-d H:i:s') . ' ';
            $content .= $title ?? '';
            $content .= ': ' . json_encode($data,JSON_UNESCAPED_UNICODE) . "\r\n";
            file_put_contents($path, $content, FILE_APPEND);
        }

    }
}
