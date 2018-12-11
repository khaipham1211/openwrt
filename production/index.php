<?php
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
    
    $serverip[$i] = $row['iprouter'];
    $namerouter[$i] = $row['tenrouter'];
    $i++;   
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/ICON.png" type="image/ico" />

    <title>Router Manager</title>

    <!-- Bootstrap -->
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
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-cog"></i> <span>Router Manager</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="main/setip.php?ip=<?php echo $serverip[0];?>"><?php echo $namerouter[0];?></a></li>
                      <li><a href="main/setip.php?ip=<?php echo $serverip[1];?>"><?php echo $namerouter[1];?></a>
                    </ul>
                  </li>
                 
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
       <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <div class="alert alert-danger alert-dismissible" id="canhbao" style="display: none" >
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>canh bao!</strong> router he thong mat ket noi
                    </div>
                </li>
              </ul>
            </nav>
          </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total client</span>
              <div class="count" id="totalclient"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Average CPU</span>
              <div class="count" id="avercpu"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Router</span>
              <div class="count green" id="totalrouter"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> <?php echo $namerouter[0];?></span>
              <div class="count" id=""><div id="g1" class="gauge" style="height: 100px"></div></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> <?php echo $namerouter[1];?></span>
               <div class="count" id=""><div id="g2" class="gauge" style="height: 100px"></div></div>
            </div>
          </div>
          <!-- /top tiles -->

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h3>Clients</h3>
                  </div>
                </div>

                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div id="chartContainer" style="height: 300px;"></div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2>NOTE</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                      <p>Tplink741</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="100"></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Tplink841</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-purple" role="progressbar" data-transitiongoal="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />
          <div class='row' id="mclient">
          </div>
          <div class='row' id="num">
            <h1 >fasdfads</h1>
          </div>
      </div>
    </div>
    

    <!-- jQuery -->
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
    (function($)

    {
      var obj;
        $(document).ready(function()
        {
        var $num = $("#num");
        $num.load("overview.php");
        var num = setInterval(function()
          {
              $num.load('overview.php');
          }, 5000);
        });
        setInterval(function()
          {
              var txt = document.getElementById("num").innerHTML;
              var obj = JSON.parse(txt);
              //alert(txt);
              document.getElementById("totalclient").innerHTML = obj.numcl;
              document.getElementById("avercpu").innerHTML = obj.avr;
              document.getElementById("totalrouter").innerHTML = obj.numrout;
          }, 5000);
        var $client = $("#mclient");
        $client.load("manageclient.php");
        var client = setInterval(function()
          {
              $client.load('manageclient.php');
          }, 5000);

    })(jQuery);
    </script>
     <script>
      window.onload = function () {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: false,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title:{
          text: "Router's Client"
        },
        axisY: {
          title: "",
          labels: {
            style: {
                width: 25
            }
          }
        },
        dataPointWidth: 40,
        data: [{        
          type: "column",  
          dataPoints: [      
            { y: 0,  label: "Router1" },
            { y: 0,  label: "Router2" }
          ]
        }]

      });

      setInterval(function() 
      {
        var txt = document.getElementById("num").innerHTML;
        var obj = JSON.parse(txt);
        var a = obj.num0;
        var b = obj.num1;
        var d = [a,b];
        var boilerColor, deltaY, yVal;
        var dps = chart.options.data[0].dataPoints;
        for (var i = 0; i < dps.length; i++) {
          dps[i] = {label: "router "+(i+1) , y: d[i]};
        }
        chart.options.data[0].dataPoints = dps; 
        chart.render();
      }, 500);   

    }
    </script>
<script>
      var g1 = new JustGage({
        id: 'g1',
        value: 100,
        min: 0,
        max: 100,
        symbol: '%',
        donut: true,
        pointer: true,
        gaugeWidthScale: 0.4,
        pointerOptions: {
          toplength: 10,
          bottomlength: 10,
          bottomwidth: 8,
          color: '#000'
        },
        customSectors: [{
          color: "#ff0000",
          lo: 50,
          hi: 100
        }, {
          color: "#00ff00",
          lo: 0,
          hi: 50
        }],
        counter: true
      });
      var g2 = new JustGage({
        id: 'g2',
        value: 100,
        min: 0,
        max: 100,
        symbol: '%',
        donut: true,
        pointer: true,
        gaugeWidthScale: 0.4,
        pointerOptions: {
          toplength: 10,
          bottomlength: 10,
          bottomwidth: 8,
          color: '#000'
        },
        customSectors: [{
          color: "#ff0000",
          lo: 50,
          hi: 100
        }, {
          color: "#00ff00",
          lo: 0,
          hi: 50
        }],
        counter: true
      });
      setInterval(function()
        {
          var txt = document.getElementById("num").innerHTML;
          var obj = JSON.parse(txt);
          var a = obj.cpu0;
          var b = obj.cpu1;
          var d = [a,b];
          for(i=0; i<3; i++){
            if(d[i]==0){
              document.getElementById("canhbao").style.display = "block";
            }
            else{
              document.getElementById("canhbao").style.display = "none";
            }
          }
          g1.refresh(a);
          g2.refresh(b);
        }, 5000);
</script>
  </body>
</html>
