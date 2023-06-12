<?php
session_start();
require_once 'lib/siteConstant.php';
require_once 'lib/Operations.php';

if ($_SESSION['userpgupdated'] == "done") {
  echo "<script>alert('Pg has been Updated..!');</script>";
  unset($_SESSION['userpgupdated']);
}
if ($_SESSION['userpgAdded'] == "done") {
  echo "<script>alert('Pg has been Added..!');</script>";
  unset($_SESSION['userpgAdded']);
}
$title = 'Pg Listing : '; 
try {
  $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
  // $con = new Operations('localhost','root','','Property_DBMS');
} catch (Exception $e) {
  echo "Error in connection to Db";
}

require_once '../../lib/siteConstant.php';
require_once '../../lib/site_variables.php';
require_once '../../vendor/autoload.php';
require_once '../../lib/Aws.php';
require_once './AjaxCalls.php';

if ($_SESSION['propertyAdded'] == 'done') {
  echo '<script>alert("Whoo...!.. Property is added .. !!")</script>';
  unset($_SESSION['propertyAdded']);
}
if ($_SESSION['Updated'] == 'done') {
  echo '<script>alert("Whoo...!.. Property is Updated .. !!")</script>';
  unset($_SESSION['Updated']);
}

require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>
<div class='col-10'>
  <div class="row ms-2  gx-0 overflow-auto" style="height: 88vh;">
    <div class="col-11">
      <div class="row d-flex ms-5 mt-5 justify-content-start ">
        <div class="col-1 d-flex justify-content-start">
          <a href="<?php echo SITE_URL; ?>PHPOPS/Application/Admin/Dashboard.php" type="button" class="btn btn-outline-primary border border-3 border-primary ">
            < Back</a>
        </div>
        <div class="col-9">
          <div class="row ">
            <div class="col-9">
              <input class="search form-control me-2 border border-success border-2" onkeyup="searchinginPg()" type="search" id='search' placeholder="Search by name / country / state / city..." aria-label="Search">
            </div>
            <div class="col-3">
              <select class="search col-2 form-select border-info border-2" onchange="searchinginPg()" id='catagory' name='catagory'>
                <option value="null">Prefered for....</option>
                <option value="Boys">Boys</option>
                <option value="Girls">Girls</option>
                <option value="Both">Both</option>

              </select>
            </div>

          </div>
        </div>
        <div class="col-2 d-flex justify-content-center">
          <a href="<?php echo SITE_URL ?>PHPOPS/Application/Admin/Addpg.php" class="btn btn-outline-success border border-3 border-danger " id="addProp">ADD Pg/co-living</a>
        </div>
      </div>

      <div class="table-responsive mt-4 ms-4">

        <table class="table  mb-0 border border-2 border-success  align-middle" id="listUsers">
          <thead class='fs-5' style='background-color: #c58ee8;'>
            <caption></caption>
            <tr class="bg- ">
              <th>Sr no.</th>
              <th>Property photo</th>
              <th>Property Discription</th>
              <th>Prefered for</th>
              <th>location</th>
              <th>Status</th>
              <th>OrderId/RentalId</th>
              <th>Regisitration Date</th>
              <th>Delete</th>
              <th>Update</th>
            </tr>
          </thead>
          <tbody class="table-group-divider" id='showtable'>
            <?php
             if (!isset($_GET['page'])) {
              $page = 1;
            } else {
              $page = $_GET['page'];
            }
      
            $results_per_page = 3;
            $page_first_result = ($page - 1) * $results_per_page;
            $pgs = $con->select("pgtable");
            $number_of_result = count($pgs);
            $number_of_page = ceil($number_of_result / $results_per_page);
            $pgs = $con->select("pgtable", "*", null, null, null, null, "$page_first_result", "$results_per_page");
            $i = $page_first_result+1;
            foreach ($pgs as $key => $value) {
              $que =  $con->select(array("country", "pgtable"), "country_name", "right join", array("country_id", "country"), "pg_id", $value['pg_id']);
              $country = ($que[0]['country_name']);

              $state = $con->select(array("State", "pgtable"), "state_name", "right join", array("state_id", "state"), "state_id", $value['state']);
              $state = ($state[0]['state_name']);

              $city = $con->select(array("pgtable", "city"), "city_name", "right join", array("city", "city_id"), "city_id", $value['city']);
              $city = ($city[0]['city_name']);

              $userowner = $con->select(array("Users", "pgtable"), array("name", 'user_type'), "right join", array("user_id", "Owner_id"), "pg_id", $value['pg_id']);
              $usernameowner = ($userowner[0]['name']);

              $username = "<b>Owner : </b>$usernameowner";
              $photo = explode(",", $value['Photos']);
            ?>
              <tr class='table-primary'>
                <td scope='row'><?php echo $i; ?></td>
                <td><img class='mt-3 w-100 rounded border border-3 border-dark p-1 ' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg<?php echo $value['pg_id'] . "/" . $photo[0]; ?>' width='100px' height='100px'></td>
                <td><b>Name : </b><b><?php echo  $value['pg_name'] ?></b><br><?php echo  $value['sort_descreption']; ?><br><b>Facilities : </b><?php echo $value['Facilities'] ?><br><b>Rooms Avalilable: </b><?php echo  $value['Room_capacity'] ?><br><b>Total Rooms : </b><?php echo  $value['Rooms'] ?></td>
                <td><b>For: </b><?php echo  $value['type'] ?></td>
                <td><b>Location : </b><br>Country : <?php echo $country . "<br> State : " . $state . "<br> City : " . $city . " <br>Street Adddress : " . $value['local_address'] . "<br>Postcode : " . $value['postcode']; ?></td>
                <td><b><?php echo  $value['status'] ?></b></td>
                <td><?php echo $username ?></td>
                <td><?php echo  $value['date_of_registration'] ?></td>
                <td class=''><button name='deletebtn' id='delpg<?php echo $value['pg_id'] ?>' class='delpg btn btn-outline-danger' type='button'><img src='https://www.svgrepo.com/show/490950/delete.svg' width='30px' height='30px' alt='delete icon'></button></td>
                <td class=''><button name='updatebtn' id='updpg<?php echo $value['pg_id'] ?>' class='updpg btn btn-outline-warning' type='button'><img src='https://www.svgrepo.com/show/422395/edit-interface-multimedia.svg' width='30px' height='30px' alt='update icon'></button></td>
              </tr>
            <?php
              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center " id="pagination">
      <ul class="pagination mt-3">
        <?php
        if (!isset($_GET['page']) || $_GET['page'] == 1) {
          $previous = $page;
        } else {
          $previous = $page - 1;
        }
        echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/Admin/pgs.php?page=$previous'>precious</a></li>";
        for ($page = 1; $page <= $number_of_page; $page++) {
          if ($_GET['page'] == $page || (!isset($_GET['page']) && $page == 1)) {
            $class = 'text-danger';
          } else {
            $class = "";
          }
          echo "<li class='page-item'><a class='page-link $class' href='" . SITE_URL . "PHPOPS/Application/Admin/pgs.php?page=" . $page . "'>  $page  </a></li>";
          // echo '<a href = "showproperties.php?page=' . $page . '">' . $page . ' </a>';  
        }
        if(isset($_GET['page'])){
          if ($_GET['page'] == $number_of_page) {
            $next = $_GET['page'];
          } else {
            $next = $_GET['page']+1;
          }
        }else{
            $next = 2;
        }
        echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/Admin/pgs.php?page=$next'>Next</a></li>";
        ?>
      </ul>
    </div>
    </div>
  </div>
</div>
<script>
  var linktoupdatepg = '<?php echo SITE_URL; ?>PHPOPS/Application/Admin/Addpg.php?id=';
  var id ;      
</script>
<script src="adminscript.js"></script>
<?php
require_once('lib/footer.php');
?>