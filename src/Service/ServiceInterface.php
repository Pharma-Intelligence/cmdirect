<?php
namespace PharmaIntelligence\CMDirect\Service;

use PharmaIntelligence\CMDirect\Message;
interface ServiceInterface
{
    const MAX_BODY_LENGTH_MULTIPART = 153;
    
    const MAX_NUMBER_MULTIPARTS_SMS_STANDARD = 255;
    
    public function queue(Message $message);
    
    public function sendImmediately(Message $message);
    
    public function send();
}

?>