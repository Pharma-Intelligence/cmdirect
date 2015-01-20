<?php
namespace PharmaIntelligence\CMDirect\Adapter;

use PharmaIntelligence\CMDirect\Response;
class EchoRequestAdapter implements RequestAdapterInterface
{
    protected $requestUrl;
    
    public function setRequestUrl($url) {
        $this->requestUrl = $url;
    }
    
    public function doRequest($requestPayload) {
        echo $requestPayload.PHP_EOL;
        return new Response('');
    }
}

?>