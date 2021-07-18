<!DOCTYPE html>
<html lang="en"> 
<head>  
  <title>Food</title>
  <meta e_name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="?page">Food Delivery Database</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href=""></a></li>
      <li><a href="?page0">Search Employee DB</a></li>
      <li><a href="?page1">Add a Employee</a></li>
      <li><a href="?page2">Search Product DB</a></li>
      <li><a href="?page3">Add Product</a></li>
      <li><a href="https://github.com/Arnamist/DBApps-3-Installation-instructions-some-security/wiki">Help</a></li>
    </ul>
  </div>
</nav>

<?php
function search_employee() {
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
  display_employee();
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

function add_employee() {
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
  display_employee();
}

function delete_employee() {
  require('cvar.php');
  $stmt = $conn->prepare("DELETE FROM employee WHERE employee_id = ?");
  $e_id = $_POST['e_id'];
  $id=(int)$e_id;
  $bind=$stmt->bind_param("i", $id);
  if (!$bind) { die($stmt->error); }
  if (!$stmt->execute()) { die($stmt->error); }
  $conn->close();
  header("Refresh:0");
}

function display_employee(){
  require('cvar.php');
  $result = $conn->query("select * from employee");
  if (isset($_POST['e_id'])) {
    delete_employee();
  }
  echo "<h3>Employees List:</h3> <div class='container'> <table class='table table-bordered'>";
  echo "<tr><th>Name</th> <th>Id</th> <th>position</th> <th>Email</th> <th>Number</th> <th>Address</th> <th>Delete</th></tr>";
  while ($row = $result->fetch_assoc()) {
     echo "<tr>";
     echo "<td>".$row["e_name"]."</td>";
     echo "<td>".$row["employee_id"]."</td>";
     echo "<td>".$row["positon"]."</td>";
     echo "<td>".$row["e_email"]."</td>";
     echo "<td>".$row["e_phoneno"]."</td>";
     echo "<td>".$row["e_address"]."</td>";
     echo "<td><form method='post'><input type='submit' name='Delete' value='  X  ' /><input type='hidden' name='e_id' value=".$row["employee_id"]." /></form></td>";
     echo "</tr>";
  }
  echo '</table></div>';
}

function search_product() {
  echo "<h3>Search for Ingredient:</h3>";
  echo '<form method="post">';
  echo '<input name="find_name" placeholder="Enter Product Name" type="string">';
  echo '<input type="submit">';
  echo '</form>';
  echo '<br/>';
  require('cvar.php');
  $stmt = $conn->prepare("SELECT * FROM product WHERE p_name = ?");
  if (isset($_POST['find_name'])) {
    $p_name = $_POST['find_name'];
    $stmt->bind_param("s", $p_name);
    $sql = "select * from product where p_name=\"".$p_name."\"";
    $stmt->execute();         
    $result = $stmt->get_result();
    
    while($row = mysqli_fetch_assoc($result)) {               
      echo "Matching Name: ". $row["p_name"]."<br>\n";
      echo "ID: " . $row["product_id"] . "<br>\n";         
    }
  }
  display_product();
}

class Product {
  public $p_id;
  public $p_name;
  public $price;
  public $source;
  public $stock;
  function __construct($id, $name, $p, $s, $q) {
      $this->p_id = $id;
      $this->p_name = $name;
      $this->price = $p;
      $this->source = $s;
      $this->stock = $q;
  }
  function store($conn) {
    require("cvar.php");
      $myid = $this->p_id;
      if ($myid < 0) {
         $result = $conn->query("select max(product_id) from product");
         while ($row = $result->fetch_array()) { $myid = $row[0] + 1; }
         $stmt = $conn->prepare("insert into product values(?,?,?,?,?)");
         $bind = $stmt->bind_param("siisi", $this->p_name, $myid, $this->price, $this->source, $this->stock);
         if (!$bind) { die($stmt->error); }
         if (!$stmt->execute()) { die($stmt->error); }
      } else {
         $stmt = $conn->prepare("update product set p_name=?, price=?, source=?, stock=? where product_id=?");
         $bind = $stmt->bind_param("siisi", $this->p_name, $this->price, $this->source, $this->stock);
         if (!$bind) { die($stmt->error); }
         if (!$stmt->execute()) { die($stmt->error); }
      }
      $conn->close();
  }
}

function add_product() {
   echo "<h3>Add Ingredient:</h3>";
    echo
    "<form  method='post'>
        <input type='hidden' name='add_pro' value='add_value'/>
          <div class='container'> <table class='table table-bordered'>
          <tr>
        <td>Name: </td><td> <input type='text' name='Name' required='true' maxlength='30'></td>
          </tr>
          <tr>
        <td>Price: </td><td> <input type='text' name='Price' required='true'></td>
          </tr>
          <tr>
        <td>Source: </td><td> <input type='text' name='Source' required='true'></td>
          </tr>
         <tr>
        <td>Quantity: </td><td> <input type='number' name='Quantity' required='true'></td>
          </tr>
        <td colspan='2'><button type='submit'> Add new </button></td>
          </tr>
          </table></div>
    </form>";
    if (isset($_POST['add_pro'])) {
      //get the submitted input
      $name = $_POST["Name"];
      $price = $_POST["Price"];
      $source = $_POST["Source"];
      $quantity = $_POST["Quantity"];
      if ( ($name == "") || (!is_numeric($price)) || ($source == "") || (!is_numeric($quantity)) ) { die("Invalid Input"); }
      require('cvar.php');
      $emp = new Product(-1, $name, $price, $source, $quantity);
      $emp->store($conn);
      $conn->close();
    }
  display_product();
}

function delete_product() {
  require('cvar.php');
  $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
  $e_id = $_POST['p_id'];
  $id=(int)$e_id;
  $bind=$stmt->bind_param("i", $id);
  if (!$bind) { die($stmt->error); }
  if (!$stmt->execute()) { die($stmt->error); }
  $conn->close();
  header("Refresh:0");
}

function display_product(){
  require('cvar.php');
  $result = $conn->query("select * from product");
  if (isset($_POST['p_id'])) {
    delete_product();
  }
  echo "<h3>Product List:</h3> <div class='container'> <table class='table table-bordered'>";
  echo "<tr><th>Name</th> <th>Product ID</th> <th>Price</th> <th>Source</th> <th>Quantity</th> <th>Delete</th></tr>";
  while ($row = $result->fetch_assoc()) {
     echo "<tr>";
     echo "<td>".$row["p_name"]."</td>";
     echo "<td>".$row["product_id"]."</td>";
     echo "<td>".$row["price"]."</td>";
     echo "<td>".$row["source"]."</td>";
     echo "<td>".$row["stock"]."</td>";
     echo "<td><form method='post'><input type='submit' name='delete' value='  X  ' /><input type='hidden' name='p_id' value=".$row["product_id"]." /></form></td>";
     echo "</tr>";
  }
  echo '</table></div>';
}

function display_default() {
  display_employee();
  display_product();
} 

$page = $_SERVER['QUERY_STRING'];
if ($page == "" || $page == "page") { display_default(); }
if ($page == "page0") { search_employee(); }
if ($page == "page1") { add_employee(); }
if ($page == "page2") { search_product(); }
if ($page == "page3") { add_product(); }
?>

</body></html>
