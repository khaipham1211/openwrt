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
	echo $i;
?>