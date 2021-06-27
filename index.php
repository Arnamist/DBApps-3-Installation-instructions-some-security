<!DOCTYPE html>
<html lang="en"> 
<head>  
  <title>PHP Test with a Bootstrap Menu</title>
  <meta e_name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Employee Database</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="?page0">Home</a></li>
      <li><a href="?page1">Search Database</a></li>
      <li><a href="?page2">Add Value</a></li>
      <li><a href="?page3">In Progress</a></li>
    </ul>
  </div>
</nav>

<?php

function display_default() {
  require('cvar.php');
  $result = $conn->query("select * from employee");
  echo "<h3>Employees List:</h3> <div class='container'> <table class='table table-bordered'>";
  echo "<tr><th>Name</th> <th>Id</th> <th>position</th> <th>Email</th> <th>Number</th> <th>Address</th></tr>";
  while ($row = $result->fetch_assoc()) {
     echo "<tr>";
     echo "<td>".$row["e_name"]."</td>";
     echo "<td>".$row["employee_id"]."</td>";
     echo "<td>".$row["positon"]."</td>";
     echo "<td>".$row["e_email"]."</td>";
     echo "<td>".$row["e_phoneno"]."</td>";
     echo "<td>".$row["e_address"]."</td>";
     echo "</tr>";
  }
  echo '</table></div>';
}

function search() {
  echo "<h3>Search Employee:</h3>";
  echo '<form method="post">';
  echo '<input name="find_name" placeholder="Enter Employee Name" type="string">';
  echo '<input type="submit">';
  echo '</form>';
  echo '<br/>';
  require('cvar.php');
  $stmt = $conn->prepare("SELECT * FROM employee WHERE e_name = ?");
  if (isset($_POST['find_name'])) {
    $e_name = $_POST['find_name'];
    $stmt->bind_param("s", $e_name);
    $sql = "select * from employee where e_name=\"".$e_name."\"";
    $stmt->execute();         
    $result = $stmt->get_result();
    
    while($row = mysqli_fetch_assoc($result)) {               
      echo "Matching Name: ". $row["e_name"]."<br>\n";
      echo "ID: " . $row["employee_id"] . "<br>\n";         
    }
  }
  display_default();
}

class Employee {
  public $e_id;
  public $e_name;
  public $position;
  public $email;
  public $no;
  public $address;
  function __construct($e_id, $e_name, $pos, $email, $no, $add) {
      $this->e_id = $e_id;
      $this->e_name = $e_name;
      $this->positon = $pos;
      $this->e_email = $email;
      $this->e_phoneno = $no;
      $this->e_address = $add;
  }
  function store($conn) {
    require("cvar.php");
      $myid = $this->e_id;
      if ($myid < 0) {
         $result = $conn->query("select max(employee_id) from employee");
         while ($row = $result->fetch_array()) { $myid = $row[0] + 1; }
         $stmt = $conn->prepare("insert into employee values(?,?,?,?,?,?)");
         $bind = $stmt->bind_param("sissis", $this->e_name, $myid, $this->positon, $this->e_email, $this->e_phoneno, $this->e_address);
         if (!$bind) { die($stmt->error); }
         if (!$stmt->execute()) { die($stmt->error); }
      } else {
         $stmt = $conn->prepare("update employee set e_name=?, positon=?, e_email=?, e_phoneno=?, e_address=? where employee_id=?");
         $bind = $stmt->bind_param("sssis", $this->e_name, $this->positon, $this->e_email, $this->e_phoneno, $this->e_address);
         if (!$bind) { die($stmt->error); }
         if (!$stmt->execute()) { die($stmt->error); }
      }
      $conn->close();
  }
}

function add_value() {
   echo "<h3>Add Employee:</h3>";
    echo
    "<form  method='post'>
        <input type='hidden' name='page_add' value='add_value'/>
          <div class='container'> <table class='table table-bordered'>
          <tr>
        <td>Name: </td><td> <input type='text' name='Name' required='true' maxlength='30'></td>
          </tr>
          <tr>
        <td>Position: </td><td> <input type='text' name='Position' required='true'></td>
          </tr>
          <tr>
        <td>Email: </td><td> <input type='text' name='Email' required='true'></td>
          </tr>
         <tr>
        <td>Phone Number: </td><td> <input type='number' name='Number' required='true'></td>
          </tr>
           <tr>
        <td>Address: </td><td> <input type='text' name='Address' required='true'></td>
          </tr>
        <td colspan='2'><button type='submit'> Add new </button></td>
          </tr>
          </table></div>
    </form>";
    if (isset($_POST['page_add'])) {
      //get the submitted input
      $name = $_POST["Name"];
      $pos = $_POST["Position"];
      $email = $_POST["Email"];
      $no = $_POST["Number"];
      $add = $_POST["Address"];
      if ( ($name == "") || ($pos == "") || ($email == "") || ($add == "") || (!is_numeric($no)) ) { die("Invalid Input"); }
      require('cvar.php');
      $emp = new Employee(-1, $name, $pos, $email, $no, $add);
      $emp->store($conn);
      $conn->close();
    }
  display_default();
}

$page = $_SERVER['QUERY_STRING'];
if ($page == "") { echo "<h3>Press the options menu to navigate</h3>"; }
if ($page == "page0") { display_default(); }
if ($page == "page1") { search(); }
if ($page == "page2") { 
  add_value(); 
}
if ($page == "page3") { echo 'In progess'; }
?>

</body></html>
