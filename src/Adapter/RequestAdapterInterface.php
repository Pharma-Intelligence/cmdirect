<?php
namespace PharmaIntelligence\CMDirect\Adapter;

interface RequestAdapterInterface
{   
    public function setRequestUrl($url);
    
    public function doRequest($requestPayload);
}

?>