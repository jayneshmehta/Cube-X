<?php
session_start();
require_once 'lib/siteConstant.php';
require_once 'lib/Operations.php';
session_start();
if (!isset($_SESSION['adminUsername']) && !isset($_SESSION['adminPassword'])) {
  header("Location: index.php");
  exit;
}

$title = 'Users Listing : ';

if ($_SESSION['useradded'] == "done") {
  echo "<script>alert('User has been Added..!');</script>";
  unset($_SESSION['useradded']);
}
if ($_SESSION['userupdated'] == "done") {
  echo "<script>alert('User has been Updated..!');</script>";
  unset($_SESSION['userupdated']);
}
try {
  $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
  // $con = new Operations('localhost','root','','property_dbms');
} catch (Exception $e) {
  echo "Failed to Connect ot Db.";
}

if ($con) {
  require_once '../../lib/siteConstant.php';
  require_once '../../lib/site_variables.php';
  require_once '../../vendor/autoload.php';
  require_once '../../lib/Aws.php';
  require_once './AjaxCalls.php';
}

require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>
<div class='col-10'>
  <div class="row ms-2  gx-0 overflow-auto" style="height: 88vh; ">
    <div class="col-11">
      <div class="row d-flex ms-5 mt-5 justify-content-start ">
        <div class="col-1 d-flex justify-content-start">
          <a href="<?php echo SITE_URL; ?>PHPOPS/Application/Admin/Dashboard.php" type="button" class="btn btn-outline-primary border border-3 border-primary ">
            < Back</a>
        </div>
        <div class="col-9">
          <div class="row ">
            <div class="col-9">
              <input class=" form-control me-2 border border-success border-2" onkeyup="searchinUser()" type="search" id='search' placeholder="Search by Name / Email / Country / State / City..." aria-label="Search">
            </div>

            <div class="col-3">
              <select id="type" class="col-2 form-select border-info border-2" onchange='searchinUser()' name='type'>
                <option value='null'>Choose catagorie...</option>
                <option value='rentel'>Rental</option>
                <option value='owner'>Owner</option>
              </select>
            </div>

          </div>
        </div>
        <div class="col-2 d-flex justify-content-center">
          <a class="btn btn-outline-danger border border-3 border-danger " href='<?php echo SITE_URL . "/PHPOPS/Application/Admin/Adduser.php"; ?>' id="adduser">ADD User</a>
        </div>
      </div>
      <div class="table-responsive mt-5">
        <table class="table  mb-0 border border-2 border-success  align-middle">
          <thead class='fs-5' style='background-color: #c58ee8;'>
            <caption></caption>
            <tr class="bg-">
              <th>Sr no.</th>
              <th>Profile photo</th>
              <th>User Details</th>
              <th>User address</th>
              <th>Owned Property</th>
              <th>Rented property</th>
              <th>Delete</th>
              <th>Update</th>
            </tr>
          </thead>
          <tbody class="table-group-divider" id='listUsers'>
            <?php

            if (!isset($_GET['page'])) {
              $page = 1;
            } else {
              $page = $_GET['page'];
            }

            $results_per_page = 3;
            $page_first_result = ($page - 1) * $results_per_page;
            $usersdata = $con->select("Users");
            $number_of_result = count($usersdata);
            $number_of_page = ceil($number_of_result / $results_per_page);
            $usersdata = $con->select("Users", "*", null, null, null, null, "$page_first_result", "$results_per_page");
            $i = $page_first_result + 1;
            foreach ($usersdata as $key => $value) {
              $que =  $con->select(array("country", "Users"), "country_name", "right join", array("country_id", "country"), "user_id", $value['user_id']);
              $country = ($que[0]['country_name']);

              $state = $con->select(array("State", "Users"), "state_name", "right join", array("state_id", "state"), "state_id", $value['state']);
              $state = ($state[0]['state_name']);

              $city = $con->select(array("city", "Users"), "city_name", "right join", array("city_id", "city"), "city_id", $value['city']);
              $city = ($city[0]['city_name']);

              $ownerlist = $con->select(array("Users", 'properties'), "*", 'right join', array('user_id', 'Owner_Id'), 'user_id', $value['user_id']);
              $property = "";
              foreach ($ownerlist as $key => $values) {
                $property .= "<b>Property Name : </b> " . $values['property_name'] . "<br>";
                $property .= "<b>streetAddress : </b> " . $values['local_address'] . "<br>";
                $propcountry = $con->select("country", "country_name", null, null, "country_id", $values['country']);
                $property .= "<b>Country : </b> " . $propcountry[0]['country_name'] . "<br>";
                $propstate = $con->select("State", "state_name", null, null, "state_id", $values['state']);
                $property .= "<b>streetAddress : </b> " . $propstate[0]['state_name'] . "<br>";
                $propcity = $con->select("city", "city_name", null, null, "city_id", $values['city']);
                $property .= "<b>City : </b> " . $propcity[0]['city_name'] . "<br><br>";
              }
              if ($property == "") {
                $property = "No Property";
              }
              $Rentallist = $con->select(array("Users", 'properties'), "*", 'right join', array('user_id', 'Rental_Id'), 'user_id', $value['user_id']);
              $rentalproperty = "";
              foreach ($Rentallist as $key => $values) {
                $rentalproperty .= "<b>Property Name : </b> " . $values['property_name'] . "<br>";
                $rentalproperty .= "<b>streetAddress : </b> " . $values['local_address'] . "<br>";
                $propcountry = $con->select("country", "country_name", null, null, "country_id", $values['country']);
                $rentalproperty .= "<b>Country : </b> " . $propcountry[0]['country_name'] . "<br>";
                $propstate = $con->select("State", "state_name", null, null, "state_id", $values['state']);
                $rentalproperty .= "<b>streetAddress : </b> " . $propstate[0]['state_name'] . "<br>";
                $propcity = $con->select("city", "city_name", null, null, "city_id", $values['city']);
                $rentalproperty .= "<b>City : </b> " . $propcity[0]['city_name'] . "<br><br>";
              }
              if ($rentalproperty == "") {
                $rentalproperty = "No Property";
              }
            ?>
              <tr class='table-primary'>
                <td scope='row'><?php echo $i ?></td>
                <td scope='row'><img class='mt-3 w-100 rounded border border-3 border-dark p-1 ' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user<?php echo $value['user_id'] . "/" . $value['profile_pic'] ?>' width='100px' height='100px'></td>
                <td scope='row'><b>Name : </b><?php echo $value['name'] . "<br><b>Email : </b>" . $value['email'] . "<br><b>Password : </b>" . md5($value['password']) . "<br><b>Contact : </b>" . $value['contact_no'] ?></td>
                <td scope='row'><b>Local Address : </b><?php echo $value['current_address'] . "<br><b> City : </b>" . $city . "<br><b>State : </b>" . $state . "<br><b>Country : </b>" . $country ?></td>
                <td scope='row'><?php echo $property ?></td>
                <td scope='row'><?php echo $rentalproperty ?></td>
                <td class=''><button name='deletebtn' id='deluser<?php echo $value['user_id'] ?>' class='deluser btn btn-outline-danger' type='button'><img src='https://www.svgrepo.com/show/490950/delete.svg' width='30px' height='30px' alt='delete icon'></button></td>
                <td class=''><button name='updatebtn' id='upduser<?php echo  $value['user_id'] ?>' class='upduser btn btn-outline-warning' type='button'><img src='https://www.svgrepo.com/show/422395/edit-interface-multimedia.svg' width='30px' height='30px' alt='update icon'></button></td>
              <?php
              $i++;
            }
              ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center " id='pagination'>
        <ul class="pagination mt-3">
          <?php
          if (!isset($_GET['page']) || $_GET['page'] == 1) {
            $previous = $page;
          } else {
            $previous = $page - 1;
          }
          echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/Admin/Users.php?page=$previous'>precious</a></li>";
          for ($page = 1; $page <= $number_of_page; $page++) {
            if ($_GET['page'] == $page || (!isset($_GET['page']) && $page == 1)) {
              $class = 'text-danger';
            } else {
              $class = "";
            }
            echo "<li class='page-item'><a class='page-link $class' href='" . SITE_URL . "PHPOPS/Application/Admin/Users.php?page=" . $page . "'>  $page  </a></li>";
            // echo '<a href = "showproperties.php?page=' . $page . '">' . $page . ' </a>';  
          }
          if (isset($_GET['page'])) {
            if ($_GET['page'] == $number_of_page) {
              $next = $_GET['page'];
            } else {
              $next = $_GET['page'] + 1;
            }
          } else {
            $next = 2;
          }
          echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/Admin/Users.php?page=$next'>Next</a></li>";
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  var id;
  var linkforupdateuser = "<?php echo SITE_URL . "/PHPOPS/Application/Admin/Adduser.php"; ?>";
</script>
<script src='adminscript.js'></script>

<?php
require_once('lib/footer.php');
?>