<?php
namespace PharmaIntelligence\CMDirect\Service;

use PharmaIntelligence\CMDirect\Adapter\RequestAdapterInterface;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumber;
use PharmaIntelligence\CMDirect\Service\ServiceInterface;
use PharmaIntelligence\CMDirect\Message;
class CMDirectService implements ServiceInterface
{    
    const MAX_MESSAGES = 1000;
    
    /**
     * 
     * @var Message[]
     */
    protected $messages = array();
    
    /**
     * 
     * @var RequestAdapterInterface
     */
    protected $requestAdapter = null;
    
    protected $productToken = null;
    
    public function __construct(RequestAdapterInterface $requestAdapter, $productToken) {
        $this->requestAdapter = $requestAdapter;
        $this->productToken = $productToken;
    }
    
    public function queue(Message $message) {
        if(count($this->messages) === self::MAX_MESSAGES)
            throw new \OverflowException('Limit of '.self::MAX_MESSAGES.' messages in queue reached');
        $this->messages[] = $message;
    }
    
    
    /**
     * 
     * @param Message $message
     * @return \PharmaIntelligence\CMDirect\Response
     */
    public function sendImmediately(Message $message) {
        $envelope = $this->createEnvelope();
        $this->addMessageToEnvelope($message, $envelope);
        return $this->requestAdapter->doRequest($envelope->saveXML());
    }
    
    protected function addMessageToEnvelope(Message $message, \DOMDocument $envelope) {
        $messages = $envelope->getElementsByTagName('MESSAGES')->item(0);
        $msg = $envelope->createElement('MSG');
        $messages->appendChild($msg);
        
        $from = $envelope->createElement('FROM', $message->getFrom());
        $msg->appendChild($from);
        
        $phoneNumber = $this->formatPhoneNumber($message->getTo());
        $to = $envelope->createElement('TO', $phoneNumber);
        $msg->appendChild($to);
        
        $dcs = $envelope->createElement('DCS', $message->getEncoding());
        $msg->appendChild($dcs);
        
        $reference  = $envelope->createElement('REFERENCE', $message->getReference());
        $msg->appendChild($reference);
        
        $body = $envelope->createElement('BODY', $message->getBody());
        $msg->appendChild($body);
        
        if(strlen($message->getBody()) > 160) {
            $parts = ceil(strlen($message->getBody())/self::MAX_BODY_LENGTH_MULTIPART);
            if($parts > self::MAX_NUMBER_MULTIPARTS_SMS_STANDARD) {
                throw new  \DomainException('Maximum number of message parts exceeded. Max: '.self::MAX_NUMBER_MULTIPARTS_SMS_STANDARD);
            }
            $minParts = $envelope->createElement('MINIMUMNUMBEROFMESSAGEPARTS', 1);
            $maxParts = $envelope->createElement('MAXIMUMNUMBEROFMESSAGEPARTS', $parts);
            $msg->appendChild($minParts);
            $msg->appendChild($maxParts);
        }
    }
    
    protected function formatPhoneNumber(PhoneNumber $number) {
        $phoneUtil = PhoneNumberUtil::getInstance();
        return $phoneUtil->format($number, PhoneNumberFormat::E164);
    }
    
    /**
     * 
     * @return \DOMDocument
     */
    protected function createEnvelope() {
        $envelope = new \DOMDocument('1.0');
        $envelope->preserveWhiteSpace = false;
        $envelope->formatOutput = true;
        $messages = $envelope->createElement('MESSAGES');
        $envelope->appendChild($messages);
        
        $authentication = $envelope->createElement('AUTHENTICATION');
        $productToken = $envelope->createElement('PRODUCTTOKEN', $this->productToken);
        $messages->appendChild($authentication);
        $authentication->appendChild($productToken);
        
        return $envelope;
    }
    
    /**
     * Empties messagequeue
     * @return \PharmaIntelligence\CMDirect\Response
     */
    public function send() {
        $envelope = $this->createEnvelope();
        foreach($this->messages as $message) {
            $this->addMessageToEnvelope($message, $envelope);
        }
        return $this->requestAdapter->doRequest($envelope->saveXML());
    }
}

?>