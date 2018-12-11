<?php
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING );
	set_time_limit (5);
	$conn = mysqli_connect('localhost', 'root', '', 'openwrt') or die ('Không thể kết nối tới database');
	$sql = 'SELECT iprouter, tenrouter, description FROM routers';
	$result = mysqli_query($conn, $sql);
	if (!$result){
		die ('Câu truy vấn bị sai');
	}
	$i = 0;

	while ($row = mysqli_fetch_array($result)){
		
		$ip[$i] = $row['iprouter'];
		$i++;		
	}
	for ( $j=0 ; $j<$i; $j++){
		
		$token[$j] = Postjson($ip[$j]);
		$num[$j] = GetNumClient($ip[$j],$token[$j]);
	}
	echo $num[0];
	echo $num[1];
	echo $num[2];

        function Postjson($ip) {
            $url = "http://$ip/cgi-bin/luci/rpc/auth";
			$ch = curl_init($url);
			$jsonData = array(
				  "id"=> 1,
			  "method"=> "login",
			  "params"=> [
				"root",
				"root"
			  ]

			);
			$jsonDataEncoded = json_encode($jsonData);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			set_time_limit(20);
			$results = curl_exec($ch);
			curl_close($ch);
			$result_arr = json_decode($results, true);
			if(is_array($result_arr)){
				$second = array_slice($result_arr, 1, 1);
				$token = implode(" ",$second);
				return $token;
			}
			else{
				return 10;
			}
         }
		 
         function GetNumClient($ip,$token) {
            $url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
			$url1 = $url2.$token;
			$ch1 = curl_init($url1);
			$jsonData1 = array(
			 "params"=> ["iwinfo wlan0 assoclist  | grep 'SNR' | wc -l"],
			 "jsonrpc"=> "2.0",
			 "id"=> 1,
			 "method"=> "exec"

			);
			$jsonDataEncoded1 = json_encode($jsonData1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonDataEncoded1);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 3); 
			curl_setopt($ch1, CURLOPT_TIMEOUT, 20);
			set_time_limit(20);
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			if(is_array($result_arr1)){
				$second1 = array_slice($result_arr1, 2, 1);
				$num = implode(" ",$second1);
				$a = trim($num);
				return $a;
			}
			else{
				$num = "0";
				return $num;
			}
			
         }
         

?>