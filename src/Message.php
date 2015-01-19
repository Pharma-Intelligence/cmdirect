<?php
namespace PharmaIntelligence\CMDirect;


use libphonenumber\PhoneNumberUtil;
class Message
{
    const ENCODING_STANDARD_GSM = 0;
    const ENCODING_UCS2 = 8;
    
    protected $body;
    protected $from;
    protected $to;
    protected $reference;
    protected $encoding = self::ENCODING_STANDARD_GSM;
    
    
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
    
    public function getBody() {
        return $this->body;
    }
    
    public function setFrom($from) {
        if(strlen($from) > 11) {
            throw new \InvalidArgumentException('From should contain 11 characters or less');
        }
        $this->from = $from;
        return $this;
    }
    
    public function getFrom() {
        return $this->from;   
    }
    
    public function setEncoding($encoding) {
        $this->encoding = $encoding;
        return $this;
    }
    
    public function getEncoding() {
        return $this->encoding;
    }
    
    public function setReference($reference) {
        if(strlen($reference) > 32 || !ctype_alnum($reference)) {
            throw new \InvalidArgumentException('Reference should be alphanumeric, maximum 32 characters');
        }
        $this->reference = $reference;
        return $this;
    }
    
    public function getReference() {
        if(empty($this->reference))
            $this->reference = spl_object_hash($this);    
        return $this->reference;
    }
    
    /**
     * @return \libphonenumber\PhoneNumber
     */
    public function getTo() {
        return $this->to;
    }
    
    public function setTo($phoneNumber) {
        $util = PhoneNumberUtil::getInstance();
        $this->to = $util->parse($phoneNumber, 'NL');
        return $this;
    }
}

?>