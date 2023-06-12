<?php
session_start();
require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';
if (!isset($_SESSION['useremail']) && !isset($_SESSION['userpassword'])) {
  header("Location: index.php");
  exit;
}
$title = "Add Property";

if (isset($_GET['propid'])) {
  $getdata = explode("  ", $_GET['propid']);
  $propertyId = base64_decode($getdata[0]);
  $OwnerId = base64_decode($getdata[1]);
  $title = "Update Property";
}
$ownerId = base64_decode($_GET['id']);
$con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
// $con = new Operations('localhost','root','','employees_dbms');
if ($con) {
  $upddata = $con->select('properties', "*", null, null, 'property_id', $propertyId);
  $upddata = $upddata[0];

  require_once '../lib/common.php';
  $objAws = new Aws($_AWS_S3_CREDENTIALS);

  if (isset($_POST['AddProp'])) {

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
    $propphoto = $_FILES['propertipic']['name'];
    $tempName = $_FILES['propertipic']['tmp_name'];
    if (empty($_FILES)) {
      $propphoto = "null";
    }
    if (isset($_GET['propid'])) {
      $ownerId = $OwnerId;
    }
    $imgtype = array("image/jpeg", "image/png", "image/jpg", "");
    $dbphoto = $con->select("properties", "Photos", null, null, 'property_id', $propertyId);
    $dbphoto = $dbphoto[0]['Photos'];
    $dbphoto = explode(",", $dbphoto);

    $propphoto = implode(',',$propphoto);
    $tempName = implode(',',$tempName);
    
    $error = true;
    foreach ($_FILES['propertipic']['type'] as $key => $value) {
      if (!in_array($value, $imgtype)) {
        $error = false;
        break;
      }
    }
    if ($propphoto == '') {
      $propphoto = $upddata['Photos'];
    }

    $cols = array("property_name", "sort_descreption", "long_discription", "Rooms", "Area", "price", "catagories", "country", "state", "city", "local_address", "postcode", "type", "Owner_Id", "status", "Photos");
    $values = array($PropName, $shortdesc, $longdesc, $room, $area, $price, $catagory, $country, $state, $city, $str_address, $Zip, $type, $ownerId, $status, $propphoto);
    if (isset($_GET['propid'])) {

      if (!$error) {
        $error = "Image should be in jpeg/png/jpg Formate only..";
      } else {
        try {
          $con->update("properties", $cols, $values, "property_id", $propertyId);
        } catch (Exception $e) {
          echo "Unable to go update thre data to Database";
        }

        $upddata = explode(",", $upddata['Photos']);
        $propphoto = explode(",", $propphoto);
        $tempName = explode(",", $tempName);

        $storefilename = "property" . $propertyId;
        if (file_exists("Admin/Files/propertyImg/$storefilename/")) {
          if ($_FILES['propertipic']['name'][0] != '') {
            $count = 1;
            foreach ($propphoto as $key => $value) {
              if ($count == 1) {
                foreach ($upddata as $keys => $values) {
                  try {
                    unlink("Admin/Files/propertyImg/$storefilename/" . $values);
                    // rmdir("Files/images/$storefilename/");
                    $objAws->deletefile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $values);
                  } catch (Exception $e) {
                    echo "Unable to delete file ";
                    echo $e;
                  }
                }
              }
              $count++;
              try {
                move_uploaded_file($tempName[$key], "Admin/Files/propertyImg/$storefilename/$value");
                $objAws->uploadFile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $value);
              } catch (Exception $e) {
                echo "Unable to uplode the image";
              }
            }
          }
        } else {
          mkdir("Admin/Files/propertyImg/$storefilename/", 0777, true);
          foreach ($propphoto as $key => $value) {
            try {
              move_uploaded_file($tempName[$key], "Admin/Files/propertyImg/$storefilename/$value");
              $objAws->uploadFile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $value);
            } catch (Exception $e) {
              echo 'Unable to uplode file';
            }
          }
        }
        $_SESSION['userpropertyupdated'] = "done";
        header('location:userinfo.php');
      }
    } else {
      $id = $con->insert("properties", $cols, $values);

      if (in_array($_FILES['propertipic']['type'], $imgtype)) {
        $error = "Image should be in jpeg/png/jpg Formate only..";
      } else {
        $storefilename = "property" . $id;
        if (!file_exists("Admin/Files/propertyImg/$storefilename/")) {
          if ($_FILES['propertipic']['name'] != '') {
            $propphoto = explode(",", $propphoto);
            $tempName = explode(",", $tempName);
            mkdir("Admin/Files/propertyImg/$storefilename/", 0777, true);
            foreach ($propphoto as $key => $value) {
              try {
                move_uploaded_file($tempName[$key], "Admin/Files/propertyImg/$storefilename/" . $value);
                $objAws->uploadFile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $value);
              } catch (Exception $e) {
                echo "Unable to uplode file";
              }
            }
          }
          $_SESSION['userpropertyAdded'] = "done";
          header('location:userinfo.php');
        }
      }

      if (isset($_GET['propid'])) {
        $propphoto = $upddata['Photos'];
      }
    }
  } 
}

require_once('userlib/header.php');
require_once('userlib/navbar.php');
?>
<div class="row d-flex ms-5 gx-0 mt-5 justify-content-start ">
  <div class="row d-flex justify-content-between ">
    <div class="col-1 d-flex justify-content-start">
      <a href="<?php echo SITE_URL; ?>PHPOPS/Application/userinfo.php" type="button" class="btn btn-outline-primary border border-3 border-primary ">
        < Back</a>
    </div>
  </div>

</div>
<div class='row gx-0 d-flex justify-content-center '>
  <div class="col-8 d-flex justify-content-center">
    <form class="row ms-3 mt-5 p-2 bg-light shadow mb-5 pb-4" method="POST" enctype="multipart/form-data">
      <div class="row  d-flex-row justify-content-center ">
        <div class='col-6 d-flex justify-content-center '>
          <img src="<?php if (isset($_GET['propid'])) {
                      echo "https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property" . $propertyId . "/";
                      $photos =  explode(",", $upddata['Photos']);
                      echo $photos[0];
                    } else {
                      echo "https://cdn4.iconfinder.com/data/icons/social-communication/142/add_photo-512.png";
                    } ?>" alt="home image" id='updateprofileprofileimg' class=" border border-2 p-2 " width="500px" height="500px" srcset="">
        </div>
        <div class='d-flex justify-content-center mt-2 gap-4'>
              <?php
              foreach ($photos as $key => $value) {
                echo "<img id='profilesrc$key' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property" . $propertyId . "/" . $value . "' alt='home image' class='updateprofileprofilesrc border border-2 p-1 img-fluid' width='100px' height='100px'  srcset=''>";
              }
              ?>
          </div>
        <div class="col-8">
          <label for="" class="form-label">Choose file</label>
          <input type="file" class="form-control" name="propertipic[]" id="propertipic" placeholder="" aria-describedby="proppic" multiple>
          <div id="proppic" class="form-text"></div>
        </div>
      </div>
      <div class="col-6">
        <label for="propname" class="form-label">Property Name : </label>
        <input type="text" class="form-control" name="propname" id="propname" aria-describedby="Errpropname" value='<?php if (isset($_GET['propid'])) {
                                                                                                                      echo $upddata['property_name'];
                                                                                                                    } else {
                                                                                                                      echo $_POST['propname'];
                                                                                                                    } ?>'>
        <small id="Errpropname" class="form-text text-muted "></small>
      </div>
      <div class="col-6">
        <label for="shortdesc" class="form-label">Short Description: </label>
        <input type="text" class="form-control" id="shortdesc" name="shortdesc" aria-describedby="Errshortdesc" value='<?php if (isset($_GET['propid'])) {
                                                                                                                          echo $upddata['sort_descreption'];
                                                                                                                        } else {
                                                                                                                          echo $_POST['shortdesc'];
                                                                                                                        } ?>'>
        <small id="Errshortdesc" class="form-text text-muted "></small>
      </div>
      <label for="longdesc">Long Description : </label>
      <div class="form-floating mt-2">
        <textarea class="form-control" placeholder="" id="longdesc" name="longdesc" aria-describedby="Errlongdesc" style="height: 100px"><?php if (isset($_GET['propid'])) {
                                                                                                                                            echo $upddata['long_discription'];
                                                                                                                                          } else {
                                                                                                                                            echo trim($_POST['longdesc']);
                                                                                                                                          }; ?></textarea>
        <small id="Errlongdesc" class="form-text text-muted "></small>
      </div>
      <div class="col-4 mt-3">
        <label for="country" class="form-label">Country</label>
        <select id="country" class="form-select" onchange='selectstate()' id='country' aria-describedby="Errcountry" name='country'>
          <option value='null'>Choose...</option>
          <?php
          $countrys = $con->select('country');

          foreach ($countrys as $key => $value) {
            echo "<option value='" . $value['country_id'] . "'";
            if (isset($_GET['propid'])) {
              if ($upddata['country'] == $value['country_id']) {
                echo 'selected';
              }
            } else if ($value['country_id'] == $_POST['country']) {
              echo 'selected';
            }
            echo ">" . $value['country_name'] . "</option>";
          }
          ?>
        </select>
        <small id="Errcountry" class="form-text text-muted "></small>
      </div>
      <div class="col-4 mt-3">
        <label for="state" class="form-label">State</label>
        <select id="state" class="form-select" onchange='selectcity()' value='' id='state' aria-describedby="Errstate" name='state'>
          <option value='null'>Choose...</option>
          <?php
          $states = $con->select('State');

          foreach ($states as $key => $value) {
            echo "<option value='" . $value['state_id'] . "'";
            if (isset($_GET['propid'])) {
              if ($upddata['state'] == $value['state_id']) {
                echo 'selected';
              }
            } else if ($value['state_id'] == $_POST['state']) {
              echo 'selected';
            }
            echo ">" . $value['state_name'] . "</option>";
          }
          ?>
        </select>
        <small id="Errstate" class="form-text text-muted "></small>

      </div>
      <div class="col-4 mt-3">
        <label for="city" class="form-label">city : </label>
        <select id="city" class="form-select" value='' id='city' aria-describedby="Errcity" name='city'>
          <option value='null'>Choose...</option>
          <?php
          $citys = $con->select('city');
          foreach ($citys as $key => $value) {
            echo "<option value='" . $value['city_id'] . "'";
            if (isset($_GET['propid'])) {
              if ($upddata['city'] == $value['city_id']) {
                echo 'selected';
              }
            } else if ($value['city_id'] == $_POST['state']) {
              echo 'selected';
            }
            echo ">" . $value['city_name'] . "</option>";
          }
          ?>
        </select>
        <small id="Errcity" class="form-text text-muted "></small>

      </div>
      <div class="col-4 mt-3">
        <label for="Address" class="form-label">Address :</label>
        <input type="text" class="form-control" id="Address" name="str_address" aria-describedby="Erraddress" placeholder="1234 Main St" value="<?php if (isset($_GET['propid'])) {
                                                                                                                                                  echo $upddata['local_address'];
                                                                                                                                                } else {
                                                                                                                                                  echo $_POST['str_address'];
                                                                                                                                                }; ?>">
        <small id="Erraddress" class="form-text text-muted "></small>

      </div>
      <div class="col-2 mt-3">
        <label for="inputZip" class="form-label">Zip :</label>
        <input type="text" class="form-control" id="Zip" name="Zip" maxlength="5" aria-describedby="Errzip" value="<?php if (isset($_GET['propid'])) {
                                                                                                                      echo $upddata['postcode'];
                                                                                                                    } else {
                                                                                                                      echo $_POST['Zip'];
                                                                                                                    } ?>">
        <small id="Errzip" class="form-text text-muted "></small>

      </div>
      <div class="col-2 mt-3">
        <label for="Area" class="form-label">Area :</label>
        <input type="text" class="form-control" id="area" name="area" aria-describedby="Errarea" value="<?php if (isset($_GET['propid'])) {
                                                                                                          echo $upddata['Area'];
                                                                                                        } else {
                                                                                                          echo $_POST['area'];
                                                                                                        } ?>">
        <small id="Errarea" class="form-text text-muted "></small>

      </div>
      <div class="col-4 mt-3">
        <label for="room" class="form-label">Room : </label>
        <select id="room" class="form-select" aria-describedby="Errrooms" name='room' value="<?php if (isset($_GET['propid'])) {
                                                                                                echo $upddata['Rooms'];
                                                                                              } else {
                                                                                                echo $_POST['room'];
                                                                                              } ?>">
          <option value='null'>Choose...</option>
          <?php
          $i = 0;
          while ($i <= 10) {
            echo "<option value= '" . $i . "'";
            if (isset($_GET['propid'])) {
              if ($upddata['Rooms'] == $i) {
                echo 'selected';
              }
            }
            echo ">" . $i . "</option>";
            $i++;
          }
          ?>
        </select>
        <small id="Errrooms" class="form-text text-muted "></small>

      </div>
      <div class="col-4 mt-3">
        <label for="price" class="form-label">Price :</label>
        <input type="number" class="form-control" id="price" name="price" aria-describedby="Errprice" value="<?php if (isset($_GET['propid'])) {
                                                                                                                echo $upddata['price'];
                                                                                                              } else {
                                                                                                                echo $_POST['price'];
                                                                                                              } ?>">
        <small id="Errprice" class="form-text text-muted "></small>

      </div>
      <div class="col-4 mt-3">
        <label for="catagory" class="form-label">catagory : </label>
        <select id="catagory" class="form-select" aria-describedby="Errcat" name='catagory'>
          <option value='null'>Choose...</option>
          <?php
          $catagorys = $con->select("catagory");
          foreach ($catagorys as $key => $value) {
            echo "<option value='" . $value['catagorie_id'] . "'";
            if (isset($_GET['propid'])) {
              if ($upddata['catagories'] == $value['catagorie_id']) {
                echo 'selected';
              }
            } else if ($value['catagorie_id'] == $_POST['catagory']) {
              echo 'selected';
            }
            echo ">" . $value['catagorie_name'] . "</option>";
          }
          ?>
        </select>
        <small id="Errcat" class="form-text text-muted "></small>
      </div>
      <div class="col-4 mt-3">
        <label for="type" class="form-label">type : </label>
        <select id="type" class="form-select" aria-describedby="Errtype" name='type'>
          <option value='null'>Choose...</option>
          <option value="Rent" <?php if (isset($_GET['propid'])) {
                                  if ($upddata['type'] == 'Rent') {
                                    echo 'selected';
                                  }
                                } else if ($_POST['type'] == 'Rent') {
                                  echo 'selected';
                                } ?>>Rent</option>
          <option value="sale" <?php if (isset($_GET['propid'])) {
                                  if ($upddata['type'] == 'sale') {
                                    echo 'selected';
                                  }
                                } else if ($_POST['type'] == 'sale') {
                                  echo 'selected';
                                } ?>>sale</option>
          <option value="lease" <?php if (isset($_GET['propid'])) {
                                  if ($upddata['type'] == 'lease') {
                                    echo 'selected';
                                  }
                                } else if ($_POST['type'] == 'lease') {
                                  echo 'selected';
                                } ?>>lease</option>
        </select>
        <small id="Errtype" class="form-text text-muted "></small>

      </div>

      <div class="col-4 mt-3">
        <label for="status" class="form-label">Status : </label>
        <select id="status" class="form-select" value='' aria-describedby="Errstatus" name='status'>
          <option value='null'>Choose...</option>
          <option value="sold" <?php if (isset($_GET['propid'])) {
                                  if ($upddata['status'] == 'sold') {
                                    echo 'selected';
                                  }
                                } else if ($_POST['status'] == 'sold') {
                                  echo 'selected';
                                } ?>>SOLD</option>
          <option value="unsold" <?php if (isset($_GET['propid'])) {
                                    if ($upddata['status'] == 'unsold') {
                                      echo 'selected';
                                    }
                                  } else if ($_POST['status'] == 'unsold') {
                                    echo 'selected';
                                  } ?>>UNSOLD</option>
        </select>
        <small id="Errstatus" class="form-text text-muted"></small>

      </div>
      <div class="col-12 mt-3 d-flex gap-3 justify-content-center ">
        <button type="submit" class="btn btn-danger" id="AddProp" name='AddProp'>Done</button>
        <!-- <button type="reset" class="btn btn-">Reset</button> -->
      </div>
    </form>
  </div>
</div>
<script src='userscript.js'></script>
<?php
require_once 'userlib/footer.php';
?>