	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
<?php
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING );
	set_time_limit (5);
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
				$mac = implode(" ",$second1);
				return $mac;
			}
			else{
				$mac = "0";
				return $mac;
			}
			
         }

	$conn = mysqli_connect('localhost', 'root', '', 'openwrt') or die ('Không thể kết nối tới database');
	$sql = 'SELECT iprouter, tenrouter, description FROM routers';
	$result = mysqli_query($conn, $sql);
	if (!$result){
		die ('Câu truy vấn bị sai');
	}
	$i = 0;
	$cputong = 0;
	$numcl = 0;
	while ($row = mysqli_fetch_array($result)){
		
		$ip[$i] = $row['iprouter'];
		$i++;		
	}
	for ( $j=0 ; $j<$i; $j++){
		
		$token[$j] = Postjson($ip[$j]);
		$mac[$j] = GetClient($ip[$j],$token[$j]);
		$cpu[$j] = trim(Getcpu($ip[$j],$token[$j]));
		$num[$j] = GetNumClient($ip[$j],$token[$j]);
		//tong client
		$numcl = $numcl+$num[$j];
		//tong cpu
		$cputong = $cputong+$cpu[$j];
	}
	echo round($cputong/3)."%";
	echo 'tong client la $numcl';
	echo "
		<div class='row tile_count'>
            <div class='col-md-2 col-sm-4 col-xs-6 tile_stats_count'>
              <span class='count_top'><i class='fa fa-user'></i> Total client</span>
              <div class='count' id='totalclient'></div>
            </div>
            <div class='col-md-2 col-sm-4 col-xs-6 tile_stats_count'>
              <span class='count_top'><i class='fa fa-clock-o'></i> Average CPU</span>
              <div class='count' id='avercpu'></div>
            </div>
            <div class='col-md-2 col-sm-4 col-xs-6 tile_stats_count'>
              <span class='count_top'><i class='fa fa-user'></i> Total Router</span>
              <div class='count green' id='totalrouter'></div>
            </div>
            <div class='col-md-2 col-sm-4 col-xs-6 tile_stats_count'>
              <span class='count_top'><i class='fa fa-user'></i> CPU1</span>
              <div class='count' id=''><div id='g1' class='gauge' style='height: 100px'></div></div>
            </div>
            <div class='col-md-2 col-sm-4 col-xs-6 tile_stats_count'>
              <span class='count_top'><i class='fa fa-user'></i> CPU2</span>
               <div class='count' id=''><div id='g2' class='gauge' style='height: 100px'></div></div>
            </div>
            <div class='col-md-2 col-sm-4 col-xs-6 tile_stats_count'>
              <span class='count_top'><i class='fa fa-user'></i> CPU3</span>
               <div class='count' id=''><div id='g3' class='gauge' style='height: 100px'></div></div>
            </div>
        </div>
          <!-- /top tiles -->

        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
              <div class='dashboard_graph'>

                <div class='row x_title'>
                  <div class='col-md-6'>
                    <h3>Clients</h3>
                  </div>
                </div>

                <div class='col-md-9 col-sm-9 col-xs-12'>
                  <div id='chartContainer' style='height: 300px;'></div>
                </div>
                <div class='col-md-3 col-sm-3 col-xs-12 bg-white'>
                  <div class='x_title'>
                    <h2>NOTE</h2>
                    <div class='clearfix'></div>
                  </div>

                  <div class='col-md-12 col-sm-12 col-xs-6'>
                    <div>
                      <p>Facebook Campaign</p>
                      <div class=''>
                        <div class='progress progress_sm' style='width: 76%;'>
                          <div class='progress-bar bg-green' role='progressbar' data-transitiongoal='100'></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Twitter Campaign</p>
                      <div class=''>
                        <div class='progress progress_sm' style='width: 76%;'>
                          <div class='progress-bar bg-green' role='progressbar' data-transitiongoal='100'></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='col-md-12 col-sm-12 col-xs-6'>
                    <div>
                      <p>Conventional Media</p>
                      <div class=''>
                        <div class='progress progress_sm' style='width: 76%;'>
                          <div class='progress-bar bg-green' role='progressbar' data-transitiongoal='100'></div>
                        </div>
                      </div>
                    </div>
                    <div>
                    </div>
                  </div>

                </div>

                <div class='clearfix'></div>
              </div>
            </div>

          </div>
          <br />";
	for ($j = 0 ; $j < $i ;  $j++ ) {
		echo "
            <div class='col-md-4 col-sm-4 col-xs-12'>
              <div class='x_panel tile fixed_height_320'>
                <div class='x_title'>
                  <h2>$ip[$j]--Client's List</h2>
                  <div class='clearfix'></div>
                </div>
                <div class='x_content'>      
                  <div class='dashboard-widget-content'>
                    <ul class='' style='list-style-type:none'>";
                    	for($z=0 ; $z <= strlen($mac[$j])/17 ; $z++){
							//echo strlen($mac[$j])/17;
							$data[$z] = substr($mac[$j],0,18);
							$mac[$j] = substr($mac[$j],18,strlen($mac[$j]));
							echo "<li><i class='fa fa-wifi' ></i><a style='font-size:125%'>  $data[$z]</a>
                      </li>";
						}
                     echo "
                    </ul>
                  </div>
                </div>
              </div>
            </div>
		";
	}        
?>
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->

    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>

    <script src="../vendors/Chart.js/dist/canvasjs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script src="../vendors/Chart.js/dist/raphael-2.1.4.min.js"></script>
    <script src="../vendors/Chart.js/dist/justgage.js"></script>
    <script>