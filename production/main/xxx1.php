<?php
	$conn = mysqli_connect('localhost', 'root', '', 'openwrt') or die ('Không thể kết nối tới database');
	$sql = 'SELECT iprouter, tenrouter, description FROM routers';
	$result = mysqli_query($conn, $sql);
	if (!$result){
		die ('Câu truy vấn bị sai');
	}
	$i = 0;
	echo "<TABLE>";
	echo "<tr>
		  <td>IP</td>
	      <td>NAME</td>
	      <td>DESCRIPTION</td>
	      </tr>";
	while ($row = mysqli_fetch_array($result)){
		echo "<TR>";
		echo "<TD>".$row['iprouter']."</TD>";
		echo "<TD>".$row['tenrouter']."</TD>";
		echo "<TD>".$row['description']."</TD>";
		echo "</TR>";
		$ip[$i] = $row['iprouter'];
		$i++;		
	}
	for ( $j=0 ; $j<$i; $j++){
		
		$token[$j] = Postjson($ip[$j]);
		$cpu[$j] = Getcpu($ip[$j],$token[$j]);
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
			echo $results1;
			curl_close($ch1);
			$result_arr1 = json_decode($results1, true);
			$second1 = array_slice($result_arr1, 2, 1);
			$cpu = implode(" ",$second1);
			return $cpu;
         }
         
      
?>
				<table class="table">
					<thead>
					  <tr>
						<th class= "col-sm-3">Performance</th>
						<th class= "col-sm-9"></th>
					  </tr>
					</thead>
					<tbody>
					<?php
					for ($j = 0 ; $j < $i ;  $j++ ) {
					  echo "<tr>
						<td>CPU1</td>
						<td>
							<div class='progress' style = 'width:150px;height:20px;background-color:white;border:solid 1px;'>
								<div id = 'progresscpu' class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='70' aria-valuemin='0' aria-valuemax='100' style='width:$cpu[0]'>$cpu[0].</div>
							</div>
						</td>
						<td>
							<form action='setip.php' method='post'>
								<input type='submit' name='ip' value='$ip[0]' style='background:none; border:solid 1px; border-radius:3px; width:110px;' />
							</form>
						</td>
					  </tr>";
					}
					  ?>
					</tbody>
				</table>