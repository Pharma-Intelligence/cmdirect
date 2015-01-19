# CMDirect
Interface library for CMDirect.nl

## Example:
````
<?php
use PharmaIntelligence\CMDirect\Service;
use PharmaIntelligence\CMDirect\Message;
use PharmaIntelligence\CMDirect\Adapter\CurlRequestAdapter;

$adapter = new CurlRequestAdapter();
$adapter->setRequestUrl('https://secure.cm.nl/smssgateway/cm/gateway.ashx');

$service = new Service($adapter, 'Your-very-secret-token');

$message = new Message();
$message
	->setFrom('YourCompany')
    ->setTo('0123456789')
    ->setBody('This is a text');
$service->queue($message);

$response = $service->send();

````
