<?php
namespace PharmaIntelligence\CMDirect\Adapter;

use PharmaIntelligence\CMDirect\Response;
class CurlRequestAdapter implements RequestAdapterInterface
{
    protected $requestUrl;
    
    protected $proxy = null;
    
    public function setRequestUrl($url) {
        $this->requestUrl = $url;
    }
    
    public function setProxyServer($host, $port) {
        $this->proxy = sprintf('%s:%s', $host, $port);
    }
    
    public function doRequest($requestPayload) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->requestUrl,
            CURLOPT_HTTPHEADER     => array('Content-Type: application/xml'),
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $requestPayload,
            CURLOPT_PROXY          => $this->proxy,
            CURLOPT_RETURNTRANSFER => true
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return new Response($response);
    }
}

?>