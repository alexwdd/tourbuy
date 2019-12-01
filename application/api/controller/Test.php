<?php
namespace app\api\controller;

class Test extends Common{

    public function index(){
    	au_sms('18537391515','5513');
    	die;
    	$messageBird = new \MessageBird\Client('rIX8b53JfP0MLYI6ESZ50ftLc');
		$message = new \MessageBird\Objects\Message();
		$message->originator = 'Tourbuy';
		//$message->recipients = ['61410867533'];
		$message->recipients = ['8618537391515'];
		$message->body = 'Hello this test message';
		      
		$result = $messageBird->messages->create($message);
		dump($result);
    }
}