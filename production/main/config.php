<?php
//echo $_POST['ipchange']; 
set_time_limit (15);
$ipchange = $_POST['ipchange'];
//$ipchange = '192.168.10.7';
session_start();
$ip = $_SESSION['ip'];
$conn = mysqli_connect("localhost","root","","openwrt");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "UPDATE routers SET iprouter='$ipchange' WHERE iprouter='$ip'";
if (mysqli_query($conn, $sql)) {
    echo "cau hinh thanh cong";
} else {
    echo "loi: " . mysqli_error($conn);
}
mysqli_close($conn);
$token = $_SESSION['token'];
$url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
$url1 = $url2.$token;
//Initiate cURL.
$ch1 = curl_init($url1);
 
//The JSON data.
$jsonData1 = array(
 "params"=> ["uci set network.lan.ipaddr=$ipchange; uci commit; reboot"],
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
 curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 0); 
//Set the content type to application/json
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 15); 
curl_setopt($ch1, CURLOPT_TIMEOUT, 15);

$result1 = curl_exec($ch1);
sleep(2);
$result_arr1 = json_decode($results1, true);
echo $result_arr1;
//$second1 = array_slice($result_arr1, 2, 1);
//$route = trim(implode(" ",$second1));
//echo $route;
//session_destroy ();
header('Location:../index.php');

?>