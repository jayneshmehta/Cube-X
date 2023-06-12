<?php
session_start();
require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';
$title = "Show PG's";
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
        <li class="breadcrumb-item text-dark  active" aria-current="page"> Pgs-/Co-living</li>
      </ol>
    </nav>
  </div>
  <form action="" method="post">
    <div class='row d-flex justify-content-center mb-4  gap-4 gx-0'>
      <div class='col-1'>
        <select class="form-select border border-2 border-primary rounded" onchange="searchpg()" id='rooms'>
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
        <select class="form-select border border-2 border-primary rounded" onchange="searchpg()" id='preference'>
          <option value='null'>View By category</option>
          <option value='Boys'>For Boys</option>
          <option value='Girls'>For Girls</option>
          <option value='Both'>Couple</option>
        </select>
      </div>

      <div class='col-2'>
        <select class="form-select border border-2 border-primary rounded" onchange="searchpg()" id='location'>
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
        <select class="form-select border border-2 border-primary rounded" onchange="searchpg()" id='NumRooms'>
          <option value='null'>Type of Sharing : </option>
          <option value='privateSharing'>Personal Sharing </option>
          <option value='doubleSharing'>Double Sharing </option>
          <option value='tripleSharing'>Triple Sharing </option>
          <option value='3+sharing'>3 + Sharing </option>
        </select>
      </div>
      <div class='col-2'>
        <select class="form-select border border-2 border-primary rounded" onchange="searchpg()" id='Price'>
          <option value='null'>Sort by price : </option>
          <option value='L2H'>Low to High</option>
          <option value='H2L'>High to Low</option>
        </select>
      </div>
      <div class='col-2 d-flex justify-content-center'>
        <div class="col-8">
          <input class="form-control me-2 col-3  border border-2 border-success rounded" onkeyup="searchpg()" id='searchele' type="search" placeholder="Search by state/name/" aria-label="Search">
        </div>
      </div>
    </div>
  </form>

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
      $pgs = $con->select("pgtable");
      $number_of_result = count($pgs);
      $number_of_page = ceil($number_of_result / $results_per_page);
      $pgs = $con->select("pgtable", "*", null, null, null, null, "$page_first_result", "$results_per_page");
      $prices =  array_column($pgs, "price");
      array_multisort($prices, SORT_ASC, $pgs);

      foreach ($pgs as $key => $value) {


        $state = $con->select("State", array('state_name'), null, null, "state_id", $value['state']);
        $state = $state[0]['state_name'];

        $country = $con->select("country", array('country_name'), null, null, "country_id", $value['country']);
        $country = $country[0]['country_name'];

        $city = $con->select("city", array('city_name'), null, null, "city_id", $value['city']);
        $city = $city[0]['city_name'];

        $photos = explode(",", $value['Photos']);

        echo  "<div class='row mt-5 gx-0' style='padding-inline: 300px;'>
        <div class='col-6 p-2 d-flex justify-content-center' >
        <img class='img-fluid p-2 border border-3 border-dark rounded' width='500px' height='500px'  src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg" . $value['pg_id'] . "/" . $photos[0] . "' alt='property img'>
        </div>
        <div class=' col-6 py-3'>
           <div class='fs-3'>" . ucfirst($value['pg_name']) . "</div><br> 
           <p class='text-muted' >" . $value['sort_descreption'] . "</p>
           <p class='text-muted' ><img src='https://www.svgrepo.com/show/513317/location-pin.svg' width='20px' height='20px' >  " . $country . " , " . $state . " , " . $city . "</p>
           <p><span class='text-danger fs-4'>Prices start at </span></p>
           <p class='ms-3 fs-3 '>" . number_format($value['price']) . " / Month</p> 
       <button class='pginfo btn btn-danger' id='info" . $value['pg_id'] . "'><strong>  More info </strong> </button> 
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
          $previous =  $page;
        } else {
          $previous =  $page - 1;
        }
        echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/showpgs.php?page=$previous'>Precious</a></li>";
        for ($page = 1; $page <= $number_of_page; $page++) {
          if ($_GET['page'] == $page || (!isset($_GET['page']) && $page == 1)) {
            $class = 'text-danger';
          } else {
            $class = "";
          }
          echo "<li class='page-item'><a class='page-link $class' href='" . SITE_URL . "PHPOPS/Application/showpgs.php?page=" . $page . "'>$page  </a></li>";
        }
        if (!isset($_GET['page'])) {
          $next = 2;
        } else if ($_GET['page'] == $number_of_page) {
          $next = $_GET['page'];
        } else {
          $next =  $_GET['page'] + 1;
        }
        echo "<li class='page-item'><a class='page-link' href='" . SITE_URL . "PHPOPS/Application/showpgs.php?page=$next'>Next</a></li>";
        ?>
      </ul>
    </div>
  </div>
</div>
<script>
  //this is used for pagination while searching..
  var id;
  $(document).on("click", ".pginfo", function() {
    var infoid = (this.id).slice(4);
    window.location.href = "<?php echo SITE_URL ?>/PHPOPS/Application/pginfo.php?pgid=" + btoa(infoid);
  });
</script>
<script src="userscript.js"></script>
<?php
require_once 'userlib/footer.php';
?>