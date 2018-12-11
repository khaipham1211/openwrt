<?php
session_start();
$ip = $_SESSION['ip'];
$token = $_SESSION['token'];
$url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
$url1 = $url2.$token;
//Initiate cURL.
$ch1 = curl_init($url1);
 
//The JSON data.
$jsonData1 = array(
 "params"=> ["iwinfo wlan0 assoclist  | grep 'SNR' | awk '{print $1}'"],
 "jsonrpc"=> "2.0",
 "id"=> 1,
 "method"=> "exec"

);

 
//Encode the array into JSON.
$jsonDataEncoded1 = json_encode($jsonData1);

//Tell cURL that we want to send a POST request.
curl_setopt($ch1, CURLOPT_POST, 1);
 
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonDataEncoded1);
 curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
//Set the content type to application/json
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 
//Execute the request
$results1 = curl_exec($ch1);

curl_close($ch1);
$result_arr1 = json_decode($results1, true);
$second1 = array_slice($result_arr1, 2, 1);
$mac = implode(" ",$second1);
//echo $mac;
for($z=0 ; $z <= strlen($mac) ; $z++){
	$data[$z] = substr($mac,0,18);
	$mac = substr($mac,18,strlen($mac));
}
$banmac = substr($mac,0,17);
$_SESSION['banmac'] = $banmac;

//echo count($data);
//echo $_SESSION['banmac'];
for ($i = 0 ; $i < count($data) ;  $i++ ) {
	echo "<tr>
		<td >Mac address</td>
		<td class= 'col-sm-8'>$data[$i]</td>
	</tr>	";
					}
?>
