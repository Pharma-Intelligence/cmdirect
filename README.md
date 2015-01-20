# CMDirect
Interface library for CMDirect.nl

## Example:
````
<?php
use PharmaIntelligence\CMDirect\Message;
use PharmaIntelligence\CMDirect\Adapter\CurlRequestAdapter;
use PharmaIntelligence\CMDirect\Service\CMCorporateService;

$adapter = new CurlRequestAdapter();
$adapter->setRequestUrl('http://cm-gateway.url/endpoint');

// Use the CMDirectService if you are a user of cmdirect.nl
// Use the CMCorporateService if you use the batch gateway for corporate users

$service = new CMCorporateService($adapter, 007, 'BondJamesBond', 'shakenNotStirred');

$message = new Message();
$message
	->setFrom('YourCompany')
    ->setTo('0123456789')
    ->setBody('Hi this is a text message');

// Use this for batches
$service->queue($message); 
$response = $service->send();

// Use this for single messages
$response = $service->sendImmediately($message);

if(!$response->isSuccessful()) {
	$error = $response->getError();
	// Do something with this error
}

````
