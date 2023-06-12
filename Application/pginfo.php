<?php
require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';
session_start();
$title = "PG Info";
if (isset($_SESSION['useremail']) && isset($_SESSION['userpassword'])) {
  $session = true;
} else {
  $session = false;
}
$propid = base64_decode($_GET['pgid']);
try {
  $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
  // $con = new Operations('localhost','root','','property_dbms');
} catch (Exception $e) {
  echo "Failed to create connection to Db..";
}

require_once "./Ajaxcalls.php";

$result = $con->select("pgtable", "Rating", null, null, "pg_id", $propid);
$dbjson =  json_decode($result[0]['Rating'], true);
$count = 0;
$sum = 0;
foreach ($dbjson as $key => $value) {
  if ($_SESSION['useremail'] == $value['user_id']) {
    $valueofrating =  $value['rating'];
    break;
  }
  $sum = $sum + $value['rating'];
  $count++;
}
if ($sum != 0) {
  $avg = ($sum / $count);
  $avgrating = round($avg);
}

///*** */
if ($con) {
  $data = $con->select("pgtable", "*", null, null, "pg_id", $propid);
  $data = $data[0];
  $state = $con->select("State", array('state_name'), null, null, "state_id", $data['state']);
  $state = $state[0]['state_name'];

  $country = $con->select("country", array('country_name'), null, null, "country_id", $data['country']);
  $country = $country[0]['country_name'];

  $city = $con->select("city", array('city_name'), null, null, "city_id", $data['city']);
  $city = $city[0]['city_name'];

  $Facilitys = explode(",", $data['Facilities']);
  $capacity = explode(",", $data['Room_capacity']);
}

require_once 'userlib/header.php';
require_once 'userlib/navbar.php';
?>
<div class='row gx-0'>
  <div class="ps-5 pt-4 p-2">
    <nav aria-label="breadcrumb ">
      <ol class="breadcrumb  text-dark">
        <li class="breadcrumb-item text-secondary "><a href="<?php echo SITE_URL ?>PHPOPS/Application/index.php" class='text-decoration-none text-secondary'>Home</a></li>
        <li class="breadcrumb-item text-secondary  " aria-current="page"><a href="<?php echo SITE_URL ?>PHPOPS/Application/showpgs.php" class='text-decoration-none text-secondary'>Pgs-/Co-living</a></li>
        <li class="breadcrumb-item text-dark  active" aria-current="page">Pg Info</li>
      </ol>
    </nav>
  </div>
  <div class='row d-flex justify-content-center' style='padding-inline: 100px;'>
    <div class='col-1 p-2  d-flex flex-column justify-content-center align-item-center'>
      <?php
      $photos = $data['Photos'];
      $photos = explode(",", $photos);
      foreach ($photos as $key => $value) {
        echo  "<img class='pgprofilesrc img-fluid p-1 my-2   border border-3 border-dark ' width='100px' height='100px'  src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg" . $data['pg_id'] . "/" . $value . "' alt='property img'>";
      }
      ?>
    </div>
    <div class='col-4 p-2 d-flex justify-content-center align-items-center'>
      <img class=' p-2 my-5 border border-3 border-dark' id='pgprofilesrc' width='500px' height='500px' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg<?php echo $data['pg_id'] . "/" . $photos[0]; ?>' alt='property img'>
    </div>
    <div class='col-6'>
      <div class='display-5'><?php echo ucfirst($data['pg_name']); ?></div> <br>
      <p class='text-muted'><?php echo  $data['sort_descreption']; ?> </p>
      <p class='text-muted'><?php echo  $data['long_discription']; ?> </p>
      <p class='text-muted'><img src='https://www.svgrepo.com/show/513317/location-pin.svg' width='20px' height='20px'> <?php echo " $country , $state , $city "; ?></p>
      <p class='text-dark'><b><?php echo "For : ";
                              if ($data['type'] == "Both") {
                                echo "Boys/Girls";
                              } else {
                                echo $data['type'];
                              }; ?></b> </p>
      <p><span class='text-danger fs-4'>Prices start at </span></p>
      <p class='ms-3 fs-3 '> <?php echo number_format($data['price']) . " / Month"; ?></p>
      <p class='text-dark '>Facilities : </p>
      <ul>
        <?php
        foreach ($Facilitys as $key => $value) {
          echo "<li>$value</li>";
        }
        ?>
      </ul>
      <p class=' text-dark fs-4 '>
        <span>Rooms : </span>
        <?php echo $data['Rooms']; ?>
      </p>
      <p>Rooms available : </p>
      <div class='d-flex'>
        <?php
        foreach ($capacity as $key => $value) {
          echo "<p class='p-2 border border-3 border-info m-2'>" . ucfirst($value) . "</p>";
        }
        ?>
      </div>
    </div>
  </div>
  <div class='row d-flex' style='padding-inline: 300px;'>
    <div class='col-6 d-flex align-items-center justify-content-start ' id='ratingdiv'>
      <div class='col-3  d-flex align-items-center justify-content-end'>
        <span class='fs-4 text-danger'><strong>Rating : </strong></span>
      </div>
      <div class='col-3  d-flex align-items-center justify-content-start'>
        <select type='number' class='border border-3 border-warning rounded me-2  px-2 ms-3' id='pgrating' <?php if (!$session) {
                                                                                                              echo 'disabled';
                                                                                                            } ?>>
          <option value="0" <?php if ($valueofrating == '0') {
                              echo 'selected';
                            }
                            if ($avgrating ==  '0' && empty($valueofrating)) {
                              echo 'selected';
                            } ?>>0</option>
          <option value="1" <?php if ($valueofrating == '1') {
                              echo 'selected';
                            }
                            if ($avgrating ==  '1' && empty($valueofrating)) {
                              echo 'selected';
                            } ?>>1</option>
          <option value="2" <?php if ($valueofrating == '2') {
                              echo 'selected';
                            }
                            if ($avgrating ==  '2' && empty($valueofrating)) {
                              echo 'selected';
                            } ?>>2</option>
          <option value="3" <?php if ($valueofrating == '3') {
                              echo 'selected';
                            }
                            if ($avgrating ==  '3' && empty($valueofrating)) {
                              echo 'selected';
                            } ?>>3 </option>
          <option value="4" <?php if ($valueofrating == '4') {
                              echo 'selected';
                            }
                            if ($avgrating ==  '4' && empty($valueofrating)) {
                              echo 'selected';
                            } ?>>4 </option>
          <option value="5" <?php if ($valueofrating == '5') {
                              echo 'selected';
                            }
                            if ($avgrating ==  '5' && empty($valueofrating)) {
                              echo 'selected';
                            } ?>>5 </option>
        </select>
        <i class="fa-solid fa-star" style="color: #ff0000;"></i>
      </div>
    </div>

  </div>
  <div class='row mt-3' style='padding-inline: 300px;'>
    <div class='col-6 d-flex justify-content-center'>
      <button class="col-6 btn btn-primary fs-4" id='pgcontact_owner' <?php if ($session) {
                                                                        echo "data-bs-toggle='modal' data-bs-target='#contactdetails'";
                                                                      } else {
                                                                        echo "data-bs-toggle='modal' data-bs-target='#userlogin'";
                                                                      } ?> value="<?php echo base64_decode($_GET['pgid']); ?>">Contact owner</button>
    </div>
    <div class='col-6 d-flex justify-content-start'>
      <a href='<?php echo SITE_URL; ?>PHPOPS/Application/showpgs.php' class="col-6 btn btn-warning fs-4">See more Pgs.. </a>
    </div>
  </div>
  <div class='row mt-5 d-flex ' style='padding-inline: 300px;'>
    <div class='col-12 d-flex justify-content-center'>
      <span class='ms-5 fs-4 text-primary'>Do you like this property .. <i class="fa-solid fa-thumbs-up"></i></span>
    </div>
  </div>
</div>
<script src="userscript.js"></script>
<?php
require_once 'userlib/footer.php';
?>