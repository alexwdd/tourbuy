<?php
$order['order_amount'] = 1;
$order['order_no'] = 'TB32345677113';

$payment['poli_account'] = 'S6104783';
$payment['poli_key'] = 'y!2C4oJ@^B6bP3n';

$order['amount'] = $order['order_amount'];

$date = time();

$json_builder = '{
	"Amount":"'. $order['order_amount'] .'",
	"CurrencyCode":"AUD",
	"MerchantReference":"'. $order['order_no'] .'",
	"MerchantReferenceFormat":"",
	"MerchantData":'.$date.',
	"MerchantHomepageURL":"http://www.xxxx.com/user.php?act=order_list",
	"SuccessURL":"http://www.xxxx.com/success.php",
	"FailureURL":"http://www.xxxx.com/failure.php",
	"CancellationURL":"http://www.xxxx.com/cancel.php",
	"NotificationURL":"http://www.xxxx.com/notify.php",
	"Timeout":"900",
	"SelectedFICode":"",
	"CompanyCode":"0",
}';

$auth = base64_encode($payment['poli_account'].":".$payment['poli_key']);

$header = array();
$header[] = 'Content-Type: application/json';
$header[] = 'Authorization: Basic '.$auth;
$ch = curl_init("https://poliapi.apac.paywithpoli.com/api/v2/Transaction/Initiate");
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER ,false);
curl_setopt($ch,CURLOPT_CAPATH ,'https://raw.githubusercontent.com/bagder/ca-bundle/master/');
curl_setopt( $ch, CURLOPT_CAINFO, "ca-bundle.crt");
curl_setopt( $ch, CURLOPT_SSLVERSION, 4);
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_builder);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec( $ch );
curl_close ($ch);
$json = json_decode($response, true);

var_dump($json);die;
header('Location: '.$json["NavigateURL"]);

return true;

//回调核心代码
$token = $_REQUEST["Token"];
$payment = get_payment($_REQUEST['code']);
$auth = base64_encode("$payment[poli_account]:$payment[poli_key]");
$header = array();
$header[] = 'Authorization: Basic '.$auth;

$ch = curl_init("https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction?token=".urlencode($token));

curl_setopt($ch,CURLOPT_SSL_VERIFYPEER ,false);
curl_setopt($ch,CURLOPT_CAPATH ,'https://raw.githubusercontent.com/bagder/ca-bundle/master/');
curl_setopt( $ch, CURLOPT_CAINFO, "ca-bundle.crt");
curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_POST, 0);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec( $ch );
curl_close ($ch);
$json = json_decode($response, true);

if($json['TransactionStatusCode'] == 'Completed' && $json['AmountPaid'] > 0){

	$order_sn = $json['MerchantReference'];
	$order_sn = trim($order_sn);
	order_paid($order_sn, 2);
}
?>