<!DOCTYPE html>
<html lang="en">
<head>
<title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
  <form method="post">
  <input name="find_name">
  <input type="submit">
</form>
<br/>
<!-- <form method="post">
  <input type="text" name="add_name">
  <input type="text" name="id">
  <input type="text" name="position">
  <input type="text" name="email">
  <input type="text" name="no">
  <input type="text" name="address">
  <br/>
  <button name="Submit" value="Submit" type="Submit">Calculate</button>
</form> -->

<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "mysql";
$dbname = "foods"; 
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo "Connection failed<br/>"; die("Connection failed: " . $conn->connect_error);
}
$sql = "select * from employee";

// Find Name
$stmt = $conn->prepare("SELECT * FROM employee WHERE e_name = ?");
if (isset($_POST['find_name'])) {
   $name = $_POST['find_name'];        
   $stmt->bind_param("s", $name);
   $sql = "select * from employee where e_name=\"".$name."\"";
   $stmt->execute();         
   $result = $stmt->get_result();
   echo $sql."<br/><br/>";
}// Add Name
// elseif (isset($_POST['Submit'])) {
//   $array = array($_POST['add_name'],$_POST['id'], $_POST['position'], $_POST['email'], $_POST['no'], $_POST['address']);
//   $sql = "insert into employee data(\"".$array[0]."\, \"".$array[1]."\, \"".$array[2]."\, \"".$array[3]."\, \"".$array[4]."\, \"".$array[5]."\,")";
//   echo $sql."<br/><br/>";
// }
// To Database
$result = $conn->query($sql);
while($row = $result->fetch_assoc())
{
   echo $row["e_name"]; echo "<br/>\n";
}


$conn->close();
?>
</body></html>