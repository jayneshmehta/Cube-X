<?php
session_start();
require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';
$title = "Show Properties";
$con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
// $con = new Operations('localhost','root','','Property_DBMS');
if ($_SESSION['Signin'] == 'done') {
  echo '<script>alert("Whoo...!.. Account is created successfully.. !!")</script>';
}

require_once './Ajaxcalls.php';

require_once 'userlib/header.php';
require_once 'userlib/navbar.php';
?>
<div class='row gx-0'>
  <div class="ps-5 pt-4 p-2">
    <nav aria-label="breadcrumb ">
      <ol class="breadcrumb  text-dark">
        <li class="breadcrumb-item text-dark "><a href="<?php echo SITE_URL ?>PHPOPS/Application/index.php" class='text-decoration-none text-secondary'>Home</a></li>
        <li class="breadcrumb-item text-dark  active" aria-current="page"> Properties</li>
      </ol>
    </nav>
  </div>
  <form action="" method="post">
    <div class='row d-flex justify-content-center mb-4  gap-4 gx-0'>
      <div class='col-1'>
        <select class="form-select border border-2 border-primary rounded" onchange="searchprop()" id='rooms'>
          <option value='null'>No. of rooms</option>
          <?php
          $i = 1;
          while ($i < 10) {
            echo "<option value='$i'>$i</option>";
            $i++;
          } ?>
          <option value="more">More...</option>
        </select>
      </div>
      <div class='col-2'>
        <select class="form-select  border border-2 border-primary rounded" onchange="searchprop()" id='catagory'>
          <option value='null'>View By category</option>
          <?php
          $catagory = $con->select("catagory");
          foreach ($catagory as $key => $value) {
            echo "<option value = '" . $value['catagorie_id'] . "'>" . $value['catagorie_name'] . "</option>";
          }
          ?>
        </select>
      </div>

      <div class='col-2'>
        <select class="form-select  border border-2 border-primary rounded" onchange="searchprop()" id='location'>
          <option value='null'>Search by location</option>
          <?php
          $city = $con->select('city');
          foreach ($city as $key => $value) {
            echo "<option value='" . $value['city_id'] . "'>" . $value['city_name'] . "</option>";
          }
          ?>
        </select>
      </div>
      <div class='col-2'>
        <select class="form-select border border-2 border-primary rounded" onchange="searchprop()" id='type'>
          <option value='null'>Type of property : </option>
          <option value="Rent">Rent</option>
          <option value="sale">Sale</option>
          <option value="lease">Lease</option>
        </select>
      </div>
      <div class='col-2'>
        <select class="form-select border border-2 border-primary rounded" onchange="searchprop()" id='Price'>
          <option value='null'>Sort by price : </option>
          <option value='L2H'>Low to High</option>
          <option value='H2L'>High to Low</option>
        </select>
      </div>
      <div class='col-2 d-flex justify-content-center'>
        <div class="col-8">
          <input class="form-control me-2 col-3  border border-2 border-success rounded" onkeyup="searchprop()" id='searchele' type="search" placeholder="Search by state/name/" aria-label="Search">
        </div>
      </div>

    </div>

  </form>
  <!-- <div class="row" style="height : 50vh;">
    <img src="https://images.pexels.com/photos/1612351/pexels-photo-1612351.jpeg?auto=compress&cs=tinysrgb&w=1600" class=" w-100" alt="..."  > -->
  <!-- <div class="card-img-overlay text-center align-middle  text-light" >
    <h5 class="card-title display-1  " style="margin-top: 300px;" > <strong>What we offer </strong></h5>
    <p class="card-text " style=" padding-inline: 300px;">
    Eu, leo tortor lacus dictum sed consectetur. Tellus enim amet, sed eu. Sit lobortis quam amet, nisi, est amet a, sociis. Varius ipsum at aenean orci phasellus tristique tincidunt laoreet ut. Tortor integer nullam lacus, purus nulla auctor dui in faucibus. Sit metus, tortor sit morbi lorem ut massa. Viverra faucibus pretium venenatis purus euismod ullamcorper. Cras tristique nunc, non dolor, non. Viverra aliquam pellentesque cum nisl. Convallis mauris id eget cursus et pulvinar lobortis.
    </p>
    <p class="card-text">Last updated 3 mins ago</p> -->
  <!-- </div> 
 </div> -->

  <div id='showlists'>

    <?php
    if ($con) {

      if (!isset($_GET['page'])) {
        $page = 1;
      } else {
        $page = $_GET['page'];
      }

      $results_per_page = 3;
      $page_first_result = ($page - 1) * $results_per_page;
      $properties = $con->select("properties");
      $number_of_result = count($properties);
      $number_of_page = ceil($number_of_result / $results_per_page);
      $properties = $con->select("properties", "*", null, null, null, null, "$page_first_result", "$results_per_page");

      $prices =  array_column($properties, "price");
      if ($price == 'L2H') {
        array_multisort($prices, SORT_ASC, $properties);
      } else {
        array_multisort($prices, SORT_DESC, $properties);
      }

      foreach ($properties as $key => $value) {


        $state = $con->select("State", array('state_name'), null, null, "state_id", $value['state']);
        $state = $state[0]['state_name'];

        $country = $con->select("country", array('country_name'), null, null, "country_id", $value['country']);
        $country = $country[0]['country_name'];

        $city = $con->select("city", array('city_name'), null, null, "city_id", $value['city']);
        $city = $city[0]['city_name'];

        if ($value['type'] == 'Rent') {
          $x = "/Month";
        } else {
          $x = ' Only';
        };

        $photos = explode(",", $value['Photos']);

        echo  "<div class='row mt-3  py-4 gx-0' style='padding-inline: 300px;'>
        <div class='col-6 p-2 d-flex justify-content-center' >
        <img class=' p-2 border border-3 border-dark rounded' width='500px' height='400px'  src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property" . $value['property_id'] . "/" . $photos[0] . "' alt='property img'>
        </div>
        <div class='col-6 py-3'>
           <div class='display-5'>" . ucfirst($value['property_name']) . "</div><br> 
           <p class='text-muted' >" . $value['sort_descreption'] . "</p>
           <p class='text-muted' ><img src='https://www.svgrepo.com/show/513317/location-pin.svg' width='20px' height='20px' >  " . $country . " , " . $state . " , " . $city . "</p>
           <p class='fs-5'>Listed for : <span>" . $value['type'] . "</span></p>
           <p><span class='text-danger fs-4'>Prices start at </span></p>
           <p class='ms-3 fs-3'>" . number_format($value['price']) . $x . "</p> 
       <button class='propertyinfo btn btn-danger ' id='info" . $value['property_id'] . "'><strong> More info </strong></button> 
        </div>
      </div>
      <hr>
      ";
      }
    }
    ?>
    <div class="d-flex justify-content-center ">
      <ul class="pagination mt-3">
        <?php
        if (!isset($_GET['page']) || $_GET['page'] == 1) {
          $previous = $page;
        } else {
          $previous = $page - 1;
        }
        echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/showproperties.php?page=$previous'>precious</a></li>";
        for ($page = 1; $page <= $number_of_page; $page++) {
          if ($_GET['page'] == $page || (!isset($_GET['page']) && $page == 1)) {
            $class = 'text-danger';
          } else {
            $class = "";
          }
          echo "<li class='page-item'><a class='page-link $class' href='" . SITE_URL . "PHPOPS/Application/showproperties.php?page=" . $page . "'>  $page  </a></li>";
          // echo '<a href = "showproperties.php?page=' . $page . '">' . $page . ' </a>';  
        }
        if ($_GET['page'] == $number_of_page) {
          $next = $_GET['page'];
        } else {
          $next = $_GET['page'] + 1;
        }
        echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/showproperties.php?page=$next'>Next</a></li>";
        ?>
      </ul>
    </div>
  </div>

</div>
<script>
    //this is used for pagination while searching..
  var id;
  $(document).on("click", ".propertyinfo", function() {
    var infoid = (this.id).slice(4);
    window.location.href = "<?php echo SITE_URL ?>/PHPOPS/Application/propertyinfo.php?id=" + btoa(infoid);
  });
</script>
<script src="userscript.js"></script>
<?php
require_once 'userlib/footer.php';
?>