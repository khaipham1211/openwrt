<?php
	//ket noi co so du lieu lay danh sach router
	$conn = mysqli_connect('localhost', 'root', '', 'openwrt') or die ('Không thể kết nối tới database');
	$sql = 'SELECT * FROM routers';
	$result = mysqli_query($conn, $sql);
	if (!$result){
		die ('Câu truy vấn bị sai');
	}
	
	//lay dia chi ip,cpu threshold, group cua moi router
	$i = 0;
	while ($row = mysqli_fetch_array($result)){
		$ip[$i] = $row['iprouter'];
		$cputhreshold[$i] = $row['cputhreshold'];
		$groupname[$i] = $row['groupname'];
		$i++;
	}
	
	while (true) {
		//lay thong tin dia chi mac va cuong do tin hieu cua moi client
		for ( $j=0 ; $j<$i; $j++){
			$token[$j] = Postjson($ip[$j]);
			$signal[$j] = Getsignal($ip[$j],$token[$j]);
			$mac[$j] = Getmac($ip[$j],$token[$j]);
			$cpu[$j] = Getcpu($ip[$j],$token[$j]);
			//echo $cpu[$j];
		}
		
	//print_r($cpu);
	//print_r($cputhreshold);
//echo $i;	
		echo "\n";
	for($j=0; $j<$i; $j++){
		//Neu router j qua tai
		echo "Kiem tra CPU $ip[$j]\n";
		if ($cpu[$j] > $cputhreshold[$j]){
			
			echo "CPU $ip[$j] qua tai \n";
			echo $cpu[$j];
			echo "\n";
			echo $cputhreshold[$j];
			echo "\n";
			//Neu con router k qua tai thi giam client
			//duyet cac router sau no
			
			for ($k = 0; $k<$i ; $k++)
				{
					if ($cpu[$k] < $cputhreshold[$k] && $groupname[$k] == $groupname[$j])
					{
						//echo "groupname $groupname[$k] & $groupname[$j]";
						$assoc = strlen($mac[$j])/18-1;
						//echo $assoc;
						if ($assoc > 0) {
							# code...
							Maxassoc($ip[$j],$token[$j],$assoc);
							echo "da gioi han lai client cho router $ip[$j] con $assoc clients\n";
							//$mac[$k] -=1;
							$mac[$j] = Getmac($ip[$j],$token[$j]);
							break;
							
						}
						else {
							echo " $ip[$j] khong the giam client nua...\n";
						}
						//$signal_arr[$j] = explode('-', $signal[$j]); // from 1
						
					}
				}
		};
	}
		
		for ( $j=0 ; $j<$i; $j++){
			$signal_arr[$j] = explode('-', $signal[$j]); // from 1
		    for($z=0 ; $z <= strlen($mac[$j]) ; $z++){
				$data[$j][$z] = substr($mac[$j],0,18);
				$mac[$j] = substr($mac[$j],18,strlen($mac[$j]));

			}

		}

	    //print_r($data)  ;
	    //print_r($signal_arr);
	    //Dieu kien qua tai
	    for($j=0; $j<$i; $j++){
	    	for ( $k = 1 ; $k < count($signal_arr[$j]) ; $k++ )
		    {
		      	if ($signal_arr[$j][$k] >= 80)
		    	{     		
					echo "cuong do tin hieu client yeu thuc hien chuyen noi ket client!";
					Kickclient($ip[$j],$token[$j],$data[$j][$k-1]);
					echo "da kick client ".$data[$j][$k-1]." co tin hieu ".$signal_arr[$j][$k]  ;
		     	}
	    	}
	    
	    }
	    sleep(5);
	    
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
			
			return (int)$cpu;
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
			 "params"=> [" ubus call hostapd.wlan0 del_client \"{'addr':'$banmac', 'reason':5, 'deauth':false, 'ban_time':3000}\""],
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
         function Maxassoc($ip,$token,$assoc) {
            $url2 = "http://$ip/cgi-bin/luci/rpc/sys?auth=";
			$url1 = $url2.$token;
			$ch1 = curl_init($url1);
			
			$jsonData1 = array(
			 "params"=> [" uci set wireless.default_radio0.maxassoc=$assoc; uci commit wireless; wifi "],
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