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
	for ( $j=0 ; $j<$i; $j++){
		
		$token[$j] = Postjson($ip[$j]);
		$cpu[$j] = trim(Getcpu($ip[$j],$token[$j]));
		$mac[$j] = GetClient($ip[$j],$token[$j]);
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
			$mac = implode(" ",$second1);
			return $mac;
         }
         function GetClient($ip,$token) {
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
         
      
?>
				<table class="table">
					<thead>
					  <tr>
						<th class= "col-sm-3">Router name</th>
						<th class= "col-sm-3">Performand</th>
						<th class= "col-sm-3">router ip</th>
						<th class= "col-sm-3">router's client</th>
					  </tr>
					</thead>
					<tbody>
					<?php
					for ($j = 0 ; $j < $i ;  $j++ ) {
					  echo "<tr>
						<td>CPU1</td>
						<td>
							<div class='progress' style = 'width:150px;height:20px;background-color:white;border:solid 1px;'>
								<div id = 'progresscpu' class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='70' aria-valuemin='0' aria-valuemax='100' style='width:$cpu[$j]%'>$cpu[$j].</div>
							</div>
						</td>
						<td>
							<form action='setip.php' method='post'>
								<input type='submit' name='ip' value='$ip[$j]' style='background:none; border:solid 1px; border-radius:3px; width:150px;' />
							</form>
						</td>
						<td>";
						for($z=0 ; $z <= strlen($mac[$j]) ; $z++){
							$data[$z] = substr($mac[$j],0,18);
							$mac[$j] = substr($mac[$j],18,strlen($mac[$j]));
							echo $data[$z];
							echo "</br>";
						}
						echo"
						</td>
					  </tr>";
					}
					  ?>
					</tbody>
				</table>