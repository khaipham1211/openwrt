
<!DOCTYPE html>
<html lang="en">
<head>
  <title>access point manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/css/css/circle.css">
  <script src="bootstrap/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>

<style>

.progress-bar-info{
	color:black;
}
</style>
</head>
<body style="background: rgba(46, 41, 45, 0.05);">
<div class="alert alert-success text-center">
  <strong>Hệ Thống Quản Lý ROUTERS tập trung</strong>
</div>
<div class="container">
  <div class="row">
    <div class="col">
      <div id="xxx">			
</div>
    </div>
  </div>
</div>
			
<script>
(function($)
{
    $(document).ready(function()
    {
		var $container = $("#xxx");
        $container.load("xxx.php");
        var refreshId = setInterval(function()
        {
            $container.load('xxx.php');
        }, 3600);
    });
})(jQuery);
</script>
<script>
	var a;
	var b ;
	var c;
	var d;
	setInterval(function()
        {
            a = document.getElementById("progresscpu0").innerHTML;
            b = document.getElementById("progresscpu1").innerHTML;
            c = document.getElementById("progresscpu2").innerHTML;
            a.trim();
            b.trim();
            c.trim();
            d = (Math.round(a)+Math.round(b)+Math.round(c))/3;
			
			if(d >80 ){
				alert("mức tải của hệ thống khá cao!"+d+"%");
			}
        }, 9000);
	
</script>

</body>
</html>