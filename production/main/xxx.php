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
		$cpu[$j] = trim(Getcpu($ip[$j],$token[$j]));
		if($cpu[$j]>30){
			echo"<div class='alert alert-danger' style='border:2px #14a8f5 dashed;background-color:white;width:30%;  '>
				  <strong>cảnh báo!</strong> routers.$ip[$j] đang quá tải.
				</div>";
		}
		$mac[$j] = GetClient($ip[$j],$token[$j]);
		$signal[$j]= GetSignal($ip[$j],$token[$j]);
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
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 3); 
			curl_setopt($ch1, CURLOPT_TIMEOUT, 20);
			set_time_limit(20);
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			if(is_array($result_arr1)){
				$second1 = array_slice($result_arr1, 2, 1);
				$cpu = implode(" ",$second1);
				return $cpu;
			}
			else{
				$cpu = 0;
				return $cpu;
			}
			
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
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 3); 
			curl_setopt($ch1, CURLOPT_TIMEOUT, 20);
			set_time_limit(20);
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			if(is_array($result_arr1)){
				$second1 = array_slice($result_arr1, 2, 1);
				$mac = implode(" ",$second1);
				return $mac;
			}
			else{
				$mac = "khong co thong tin";
				return $mac;
			}
			
         }
        function GetSignal($ip,$token) {
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
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 3); 
			curl_setopt($ch1, CURLOPT_TIMEOUT, 20);
			set_time_limit(20);
			$results1 = curl_exec($ch1);
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			if(is_array($result_arr1)){
				$second1 = array_slice($result_arr1, 2, 1);
				$sig = implode(" ",$second1);
				return $sig;
			}
			else{
				$sig = "khong co thong tin";
				return $sig;
			}
			
         }
         
         
      
?>
				<table class="table" style="border: 3px solid #14a8f5; background-color: white;">
					<thead style="border-color: #14a8f5 ">
					  <tr>
						<th class= "">Router Name</th>
						<th class= "">Performand</th>
						<th class= "">Router Ip</th>
						<th class= "">Router's Client</th>
						<th class= "">Signal's Client</th>
						<th class= "">Restrict Client</th>
					  </tr>
					</thead>
					<tbody style="border: 1px solid #14a8f5">
					<?php
					$tong = 0;
					for ($j = 0 ; $j < $i ;  $j++ ) {
					  echo "<tr>
						<td>CPU$j</td>
						<td>
							<div class='progress' style = 'width:150px;height:20px;background-color:white;border:solid 1px;'>
								<div id = 'progresscpu$j' class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='70' aria-valuemin='0' aria-valuemax='100' style='width:$cpu[$j]%'>$cpu[$j]</div>
							</div>
						</td>
						<td>
							<form action='setip.php' method='post'>
								<input type='submit' name='ip' value='$ip[$j]' style='background:none; border:solid 1px; border-radius:3px; width:150px;' />
							</form>
						</td>
						<td>";
						//$count = 0;
						$tong = $tong+$cpu[$j];
						$count= $count + strlen($mac[$j])/17;
						for($z=0 ; $z <= strlen($mac[$j])/17 ; $z++){
							//echo strlen($mac[$j])/17;
							$data[$z] = substr($mac[$j],0,18);
							$mac[$j] = substr($mac[$j],18,strlen($mac[$j]));
							echo $data[$z];
							echo "</br>";
						}
						echo"
						</td>
						<td>
						$signal[$j];
						</td>
						<td>
						<form class='form-inline' action='restrict.php'>
						    <select class='form-control' id='' style='width:70px;'>
						      <option>1</option>
						      <option>2</option>
						      <option>3</option>
						      <option>4</option>
						      <option>5</option>
						    </select>
						    <button type='submit' class='btn btn-default'>Submit</button>
						</form>
						</td>
					  </tr>";
					}

					  ?>
					</tbody>
				</table>
				    <div class="col-sm">
				      <strong>Số lượng nối kết:</strong><h3 style="margin-left: 40px; background-color: rgb(82, 77, 124); border-radius: 40px;width: 40px; color:white; padding:10px"><?php print round($count);?></h3>
				    </div>
				    <div class="col-sm">
				      <strong>Tải trung bình hệ thống:</strong>
						<div class="page">
			                <div class="c100 p<?php echo round($tong/3); ?>">
			                    <span><?php echo round($tong/3);?></span>
			                    <div class="slice">
			                        <div class="bar"></div>
			                        <div class="fill"></div>
			                    </div>
			                </div>   
		        		</div>
				    </div>
					
