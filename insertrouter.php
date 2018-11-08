<!DOCTYPE html>
<html lang="en">
<head>
  <title>access point manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>

</head>
<body>
<form method = "post" action = "insertrouter.php">
  <div class="form-group">
    <label for="exampleInputEmail1">IP</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="" name ="ip">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Name</label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="" name ="name">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Description</label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="" name = "des">
  </div>
 <div class="form-group">
    <label for="exampleInputPassword1">Cpu Threshold</label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="" name = "cpu">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Group</label>
    <input type="number" class="form-control" id="exampleInputPassword1" placeholder="" name = "group">
  </div>
  <button type="submit" class="btn btn-primary" name="submit" >Submit</button>
</form>
</body>
</html>
<?php
if(isset($_POST["submit"])){
$ip = $_POST["ip"];
$name = $_POST["name"];
$des = $_POST["des"];
$cpu = $_POST["cpu"];
$groupname = $_POST["group"];
$conn = mysqli_connect('localhost', 'root', '', 'openwrt') or die ('Không thể kết nối tới database');
$sql = "insert into routers values('$ip','$name','$des','$cpu','$groupname')";
$result = mysqli_query($conn, $sql);
 
if ($result) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
}
?>