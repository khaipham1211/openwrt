<?php
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

	while(true){

	for ( $j=0 ; $j<$i; $j++){
		$token[$j] = Postjson($ip[$j]);
		$signal[$j] = Getsignal($ip[$j],$token[$j]);
	}
      $signal_arr = explode('-', $signal[0]); // from 1
      // Dieu kien qua tai
      for ( $k = 1 ; $k < count($signal_arr) ; $k++ )
      {
      	if ($signal_arr[$k] >= 80)
      	{

      		for ( $j=0 ; $j<$i; $j++){
				$token[$j] = Postjson($ip[$j]);
				$mac[$j] = Getmac($ip[$j],$token[$j]);
			}
		for($z=0 ; $z <= strlen($mac[0]) ; $z++){
			$data[$z] = substr($mac[0],0,17);
			$mac[0] = substr($mac[0],17,strlen($mac[0]));
		}
      	}
      	//$banmac = $data[$k-1];
      	//$token[$j] = Postjson($ip[$j]);
      	//Kickclient($ip[0],$token[0],$banmac);
      }


	sleep(1);
	}
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
			$results = curl_exec($ch);
			curl_close($ch);
			$result_arr = json_decode($results, true);
			$second = array_slice($result_arr, 1, 1);
			$token = implode(" ",$second);
			return $token;
         }
		 
		function Getcpu($ip,$token) {
            $url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
			$url1 = $url2.$token;
			$ch1 = curl_init($url1);
			$jsonData1 = array(
			 "params"=> ["sh cpu.sh"],
			 "jsonrpc"=> "2.0",
			 "id"=> 1,
			 "method"=> "exec"

			);
			$jsonDataEncoded1 = json_encode($jsonData1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonDataEncoded1);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			$second1 = array_slice($result_arr1, 2, 1);
			$cpu = implode(" ",$second1);
			return $cpu;
         }

         function Getmac($ip,$token) {
            $url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
			$url1 = $url2.$token;
			$ch1 = curl_init($url1);
			$jsonData1 = array(
			 "params"=> ["iwinfo wlan0 assoclist  | grep 'SNR' | awk '{print $1}'"],
			 "jsonrpc"=> "2.0",
			 "id"=> 1,
			 "method"=> "exec"

			);
			$jsonDataEncoded1 = json_encode($jsonData1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonDataEncoded1);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			$second1 = array_slice($result_arr1, 2, 1);
			$mac = implode(" ",$second1);
			return $mac;
         }

         function Getsignal($ip,$token) {
            $url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
			$url1 = $url2.$token;
			$ch1 = curl_init($url1);
			$jsonData1 = array(
			 "params"=> ["iwinfo wlan0 assoclist  | grep 'SNR' | awk '{print $2}'"],
			 "jsonrpc"=> "2.0",
			 "id"=> 1,
			 "method"=> "exec"

			);
			$jsonDataEncoded1 = json_encode($jsonData1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonDataEncoded1);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			$second1 = array_slice($result_arr1, 2, 1);
			$signal = implode(" ",$second1);
			return $signal;
         }
         function Kickclient($ip,$token,$banmac) {
            $url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
			$url1 = $url2.$token;
			$ch1 = curl_init($url1);
			$jsonData1 = array(
			 "params"=> [" ubus call hostapd.wlan0 del_client \"{'addr':'$banmac', 'reason':5, 'deauth':false, 'ban_time':0}\""],
			 "jsonrpc"=> "2.0",
			 "id"=> 1,
			 "method"=> "exec"

			);
			$jsonDataEncoded1 = json_encode($jsonData1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonDataEncoded1);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			$second1 = array_slice($result_arr1, 2, 1);
			$signal = implode(" ",$second1);
			return $signal;
         }
          
?>