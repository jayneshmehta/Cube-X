<?php
session_start();
require_once 'lib/siteConstant.php';
require_once 'lib/Operations.php';
if (!isset($_SESSION['adminUsername']) && !isset($_SESSION['adminPassword'])) {
  header("Location: index.php");
  exit;
}
$title = 'Update Property : ';
require_once '../../lib/siteConstant.php';
require_once '../../lib/site_variables.php';
require_once '../../vendor/autoload.php';
require_once '../../lib/Aws.php';

$propId = $_GET['id'];
$con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
// $con = new Operations('localhost','root','','employees_dbms');
if ($con) {
  $result = $con->select('properties', "*", null, null, "property_id", $propId);
  $result = $result[0];
  $photos = explode(",", $result['Photos']);


  if (isset($_POST['update'])) {

    $PropName = $_POST['propname'];
    $shortdesc = $_POST['shortdesc'];
    $longdesc = $_POST['longdesc'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $str_address = $_POST['str_address'];
    $Zip = $_POST['Zip'];
    $catagory = $_POST['catagory'];
    $area = $_POST['area'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $room = $_POST['room'];
    $OwnerId = $_POST['OwnerId'];
    $RentalId = $_POST['RentalId'];
    if ($_FILES['prop_pic']['name'][0] == "") {
      $filename = $result['Photos'];
    } else {
      $filename = '';
      foreach ($_FILES['prop_pic']['name'] as $key => $value) {
        $filename .=  $value;
        if ($key < count($_FILES['prop_pic']['name']) - 1) {
          $filename .= ",";
        }
      };
    }

    $tmpfile = '';
    foreach ($_FILES['prop_pic']['tmp_name'] as $key => $value) {
      $tmpfile .=  $value;
      if ($key < count($_FILES['prop_pic']['tmp_name']) - 1) {
        $tmpfile .= ",";
      }
    }

    $cols = array("property_name", "sort_descreption", "long_discription", "Rooms", "Area", "price", "catagories", "country", "state", "city", "local_address", "postcode", 'price', "type", "status", "Photos", "Owner_Id", "Rental_Id");

    $values = array("$PropName", "$shortdesc", "$longdesc", "$room", "$area", "$price", "$catagory", "$country", "$state", "$city", "$str_address", "$Zip", "$price", $type, "$status", "$filename", "$OwnerId", "$RentalId");
    if ($_FILES['prop_pic']['name'][0]  != "") {

      $filename = explode(",", $filename);
      $tmpfile = explode(",", $tmpfile);
      $dbphotos = explode(",", $result['Photos']);
      $objAws = new Aws($_AWS_S3_CREDENTIALS);
      $storefilename = 'property' . $propId;
      if (file_exists("Files/propertyImg/$storefilename/")) {
        if ($_FILES['prop_pic']['name'] != '') {
          $count = 1;
          foreach ($filename as $key => $value) {
            if ($count == 1) {
              foreach ($dbphotos as $keys => $photo) {
                unlink("Files/propertyImg/$storefilename/" . $photo);
                $objAws->deletefile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $photo);
              }
            }
            $count++;
            move_uploaded_file($tmpfile[$key], "Files/propertyImg/$storefilename/" . $value);
            $objAws->uploadFile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $value);
          }
        }
      } else {
        mkdir("Files/propertyImg/$storefilename/", 0777, true);
        foreach ($filename as $key => $value) {
          move_uploaded_file($tmpfile[$key], "Files/propertyImg/$storefilename/" . $value);
          $objAws->uploadFile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $value);
        }
      }
    }
    $con->update("properties", $cols, $values, "property_id", $propId);
    $_SESSION["Updated"] = 'done';
    header("Location: properties.php");
  }
}


require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>
<div class='col-10'>
  <div class="row ms-2 maindiv gx-0 overflow-auto" style="height: 88vh;">
    <div class="col-10">
      <form class="row ms-3 mt-5 p-2 border border-3 border-primary" method="POST" enctype="multipart/form-data">
        <div class="row  d-flex-row justify-content-center ">
          <div class='col-md-8'>
            <div class=' d-flex justify-content-center '>
              <img id='profilesrc' src="https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/<?php echo "property$propId/" . $photos[0]; ?>" alt="home image" class=" border border-2 p-2" height='500px' width="500px" srcset="">
            </div>
            <div class='d-flex justify-content-center mt-2 gap-4'>
              <?php
              foreach ($photos as $key => $value) {
                echo "<img id='profilesrc$key' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property" . $propId . "/" . $photos[$key] . "' alt='home image' class='profilesrc border border-2 p-1 img-fluid' width='100px' height='100px'  srcset=''>";
              }
              ?>
            </div>

          </div>
          <div class="col-md-8">
            <label for="" class="form-label">Choose file</label>
            <input type="file" class="form-control" name="prop_pic[]" id="prop_pic" placeholder="" aria-describedby="fileHelpId" multiple>
            <div id="fileHelpId" class="form-text"></div>
          </div>
        </div>
        <div class="col-md-6">
          <label for="propname" class="form-label">Property Name : </label>
          <input type="text" class="form-control" name="propname" id="propname" value='<?php echo $result['property_name'] ?>'>
          <small id="Errpropname" class="form-text text-muted "></small>
        </div>
        <div class="col-md-6">
          <label for="shortdesc" class="form-label">Short Description: </label>
          <input type="text" class="form-control" id="shortdesc" name="shortdesc" value='<?php echo $result['sort_descreption'] ?>'>
          <small id="Errshortdesc" class="form-text text-muted "></small>
        </div>
        <label for="longdesc">Long Description : </label>
        <div class="form-floating mt-2">
          <textarea class="form-control" placeholder="" id="longdesc" name="longdesc" style="height: 100px"><?php echo trim($result['long_discription']); ?></textarea>
          <small id="Errlongdesc" class="form-text text-muted "></small>
        </div>

        <div class="col-md-4 mt-3">
          <label for="country" class="form-label">Country</label>
          <select id="country" class="form-select" onchange='selectstate()' id='country' name='country'>
            <option selected>Choose...</option>
            <?php
            $countrys = $con->select('country');
            foreach ($countrys as $key => $value) {
              echo "<option value='" . $value['country_id'] . "'";
              if ($value['country_id'] == $result['country']) {
                echo 'selected';
              }
              echo ">" . $value['country_name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errcountry" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="state" class="form-label">State</label>
          <select id="state" class="form-select" onchange='selectcity()' value='' id='state' name='state'>
            <option disabled>Choose...</option>
            <?php
            $states = $con->select(array('State', 'country'), array('state_name', 'state_id'), 'right join', array('country_id', 'country_id'), 'B.country_id', $result['country']);
            foreach ($states as $key => $value) {
              echo "<option value='" . $value['state_id'] . "'";
              if ($value['state_id'] == $result['state']) {
                echo 'selected';
              }
              echo ">" . $value['state_name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errstate" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="city" class="form-label">city : </label>
          <select id="city" class="form-select" value='' id='city' name='city'>
            <option selected disabled>Choose...</option>
            <?php
            $citys = $con->select(array('city', 'State'), array('city_name', 'city_id'), 'right join', array('state_id', 'state_id'), 'B.state_id', $result['state']);
            foreach ($citys as $key => $value) {
              echo "<option value='" . $value['city_id'] . "'";
              if ($value['city_id'] == $result['city']) {
                echo 'selected';
              }
              echo ">" . $value['city_name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errcity" class="form-text text-muted "></small>
        </div>
        <div class="col-4 mt-3">
          <label for="inputAddress" class="form-label">Address :</label>
          <input type="text" class="form-control" id="inputAddress" name="str_address" placeholder="1234 Main St" value="<?php echo $result['local_address']; ?>">
          <small id="Erraddress" class="form-text text-muted "></small>
        </div>
        <div class="col-md-2 mt-3">
          <label for="inputZip" class="form-label">Zip :</label>
          <input type="text" class="form-control" id="inputZip" name="Zip" maxlength="6" value="<?php echo $result['postcode']; ?>">
          <small id="Errzip" class="form-text text-muted "></small>
        </div>
        <div class="col-md-2 mt-3">
          <label for="Area" class="form-label">Area :</label>
          <input type="text" class="form-control" id="area" name="area" value="<?php echo $result['Area']; ?>">
          <small id="Errarea" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="room" class="form-label">Room : </label>
          <select id="room" class="form-select" value='' name='room'>
            <option selected disabled>Choose...</option>
            <?php
            $rooms = $con->select("properties", "Rooms", null, null, "property_id", $result['property_id']);
            $rooms = $rooms[0];
            $i = 0;
            while ($i <= 10) {
              echo "<option value='" . $i . "'";
              if ($i == $result['Rooms']) {
                echo 'selected';
              }
              echo ">" . $i . "</option>";
              $i++;
            }
            ?>
          </select>
          <small id="Errrooms" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="catagory" class="form-label">catagory : </label>
          <select id="catagory" class="form-select" value='' name='catagory'>
            <option selected>Choose...</option>
            <?php
            $catagorys = $con->select("catagory");
            $catagorys = $catagorys;
            foreach ($catagorys as $key => $value) {
              echo "<option value='" . $value['catagorie_id'] . "'";
              if ($value['catagorie_id'] == $result['catagories']) {
                echo 'selected';
              }
              echo ">" . $value['catagorie_name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errcat" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="type" class="form-label">type : </label>
          <select id="type" class="form-select" value='' name='type'>
            <option selected disabled>Choose...</option>
            <option value="Rent" <?php if ($result['type'] == 'Rent') {
                                    echo 'selected';
                                  } ?>>Rent</option>
            <option value="sale" <?php if ($result['type'] == 'sale') {
                                    echo 'selected';
                                  } ?>>sale</option>
            <option value="lease" <?php if ($result['type'] == 'lease') {
                                    echo 'selected';
                                  } ?>>lease</option>
          </select>
          <small id="Errtype" class="form-text text-muted "></small>
        </div>
        <div class="col-4 mt-3">
          <label for="inputAddress" class="form-label">Price :</label>
          <input type="number" class="form-control" id="price" name="price" value="<?php echo $result['price']; ?>">
        </div>
        <small id="Errprice" class="form-text text-muted "></small>
        <div class="col-md-4 mt-3">
          <label for="OwnerId" class="form-label">Owner : </label>
          <select id="OwnerId" class="form-select" value='' name='OwnerId'>
            <option selected disabled>Choose...</option>
            <?php
            $owner_id = $con->select("Users", "*", null, null, "user_type", "owner");
            foreach ($owner_id as $key => $value) {
              echo "<option value='" . $value['user_id'] . "'";
              if ($value['user_id'] == $result['Owner_Id']) {
                echo 'selected';
              }
              echo ">" . $value['name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errowner" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="RentalId" class="form-label">Rental Id : </label>
          <select id="RentalId" class="form-select" value='' name='RentalId'>
            <option selected  value = "norental" >Choose...</option>
            <?php
            $Rental_id = $con->select("Users", "*", null, null, "user_type", "rentel");
            foreach ($Rental_id as $key => $value) {
              echo "<option value='" . $value['user_id'] . "'";
              if ($value['user_id'] == $result['Rental_Id']) {
                echo 'selected';
              }
              echo ">" . $value['name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errrent" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="status" class="form-label">Status : </label>
          <select id="status" class="form-select" value='' name='status'>
            <option selected disabled>Choose...</option>
            <option value="SOLD" <?php if ($result['status'] == 'sold') {
                                    echo 'selected';
                                  } ?>>SOLD</option>
            <option value="UNSOLD" <?php if ($result['status'] == 'unsold') {
                                      echo 'selected';
                                    } ?>>UNSOLD</option>
          </select>
          <small id="Errstatus" class="form-text text-muted "></small>
        </div>
        <div class="col-12 my-5 d-flex gap-3 justify-content-center ">
          <button type="submit" class="btn btn-warning" name='update' id='update'>Update</button>
        </div>
      </form>
    </div>
    <script>
      $(document).on('click', ".profilesrc", function() {
        $("#profilesrc").attr("src", this.src);
      });
    </script>
    <script src='adminscript.js'></script>
    <?php
    require_once('lib/footer.php');
    ?>