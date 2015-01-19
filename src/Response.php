<?php
namespace PharmaIntelligence\CMDirect;

class Response
{
    protected $responseText;
    
    public function __construct($responseText) {
        $this->responseText = $responseText;    
    }
    
    public function isSuccessful() {
        return empty($this->responseText);
    }
    
    public function getError() {
        return str_replace('Error: ', '', $this->responseText);
    }
}

?>