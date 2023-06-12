<?php
session_start();
require_once 'lib/siteConstant.php';
require_once 'lib/Operations.php';
if (!isset($_SESSION['adminUsername']) && !isset($_SESSION['adminPassword'])) {
  header("Location: index.php");
  exit;
}
$title = 'Add PG : ';

if (isset($_GET['id'])) {
  $title = 'Update PG : ';
  $getid = $_GET['id'];
}

require_once '../../lib/siteConstant.php';
require_once '../../lib/site_variables.php';
require_once '../../vendor/autoload.php';
require_once '../../lib/Aws.php';

try {
  $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
  // $con = new Operations('localhost','root','','employees_dbms');
} catch (Exception $e) {
  echo "Error in connection to Db";
}

if ($con) {
  $upddata = $con->select('pgtable', "*", null, null, 'pg_id', $getid);
  $upddata = $upddata[0];
  $facilities = explode(",", $upddata['Facilities']);
  $Room_capacity = explode(",", $upddata['Room_capacity']);

  if (isset($_POST['AddPg'])) {

    $PgName = $_POST['propname'];
    $shortdesc = $_POST['shortdesc'];
    $longdesc = $_POST['longdesc'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $str_address = $_POST['str_address'];
    $Zip = $_POST['Zip'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $room = $_POST['room'];
    $pgtype = $_POST['pggender'];
    $roomtype = $_POST['pgRoom'];
    $facilities = $_POST['facilities'];
    $facilities = implode(",", $facilities);
    $roomtype = implode(",", $roomtype);
    $propphoto = $_FILES['propertipic']['name'];
    $tempName = $_FILES['propertipic']['tmp_name'];
    $ownerId = $_POST['OwnerId'];
    if (empty($_FILES)) {
      $propphoto = "null";
    }
   


    $imgtype = array("image/jpeg", "image/png", "image/jpg", "");
    $dbphoto = $con->select("properties", "Photos", null, null, 'property_id', $propertyId);
    $dbphoto = $dbphoto[0]['Photos'];
    $dbphoto = explode(",", $dbphoto);

    $propphoto = implode(",", $propphoto);


    $tempName = implode(",", $tempName);

    $error = true;
    foreach ($_FILES['propertipic']['type'] as $key => $value) {
      if (!in_array($value, $imgtype)) {
        $error = false;
        break;
      }
    }
    if (isset($_GET['id']) && $propphoto == '') {
      $propphoto = $upddata['Photos'];
    }
    if (!isset($_GET['id']) && $propphoto == '') {
      $propphoto = "no photo";
    }
    $cols = array("pg_name", 'sort_descreption', "Facilities", "Rooms", "Room_capacity", "price", "country", "state", "city", "local_address", "postcode", "Photos", "Owner_Id", "type", "long_discription", "status");
    $values = array($PgName, $shortdesc, $facilities, $room, $roomtype, $price, $country, $state, $city, $str_address, $Zip, $propphoto, $ownerId, $pgtype, $longdesc, $status);


    if (isset($_GET['id'])) {
      try {
        $con->update("pgtable", $cols, $values, "pg_id", $getid);
      } catch (Exception $e) {
        echo "Faild to update table";
      }

      if (!$error) {
        $error = "Image should be in jpeg/png/jpg Formate only..";
      } else {

        $upddata = explode(",", $upddata['Photos']);
        $propphoto = explode(",", $propphoto);
        $tempName = explode(",", $tempName);
        $count = 1;
        foreach ($propphoto as $key => $value) {
          $storefilename = "pg" . $getid;
          if (file_exists("Files/Pgimages/$storefilename/")) {
            $objAws = new Aws($_AWS_S3_CREDENTIALS);
            if ($_FILES['propertipic']['name'][0] != '') {
              if ($count <= 1) {
                foreach ($upddata as $keys => $values) {
                  try {
                    unlink("Files/Pgimages/$storefilename/" . $values);
                    $objAws->deletefile($_AWS_S3_CREDENTIALS, "Pgimages/" . $storefilename . "/" . $values);
                  } catch (Exception $e) {
                    echo "File not found for delete";
                  }
                }
                $count++;
              }
              // rmdir("Files/images/$storefilename/");
              try {
                move_uploaded_file($tempName[$key], "Files/Pgimages/$storefilename/$value");
                $objAws->uploadFile($_AWS_S3_CREDENTIALS, "Pgimages/" . $storefilename . "/" . $value);
              } catch (Exception $e) {
                echo "Failed to upload a file";
              }
            }
          } else {
            mkdir("Files/Pgimages/$storefilename/", 0777, true);
            try {
              $objAws = new Aws($_AWS_S3_CREDENTIALS);
              move_uploaded_file($tempName[$key], "Files/Pgimages/$storefilename/$value");
              $objAws->uploadFile($_AWS_S3_CREDENTIALS, "Pgimages/" . $storefilename . "/" . $value);
            } catch (Exception $e) {
              echo "Failed to add file";
            }
          }
        }
        $_SESSION['userpgupdated'] = "done";
        header("location:" . SITE_URL . "PHPOPS/Application/Admin/pgs.php");
      }
    } else {
      $id = $con->insert("pgtable", $cols, $values);
      if ($error) {
        $propphoto = explode(",", $propphoto);
        $tempName = explode(",", $tempName);

        foreach ($propphoto as $key => $value) {
          $storefilename = "pg" . $id;
          if ($_FILES['propertipic']['name'][0] != '') {
            $objAws = new Aws($_AWS_S3_CREDENTIALS);
            mkdir("Files/Pgimages/$storefilename/", 0777, true);
            move_uploaded_file($tempName[$key], "Files/Pgimages/$storefilename/$propphoto[$key]");
            $objAws = new Aws($_AWS_S3_CREDENTIALS);
            $objAws->uploadFile($_AWS_S3_CREDENTIALS, "Pgimages/" . $storefilename . "/" . $value);
          }
        }
        $_SESSION['userpgAdded'] = "done";
        header("location: " . SITE_URL . "PHPOPS/Application/Admin/pgs.php");
      }
    }
  }
}

require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>
<div class='col-10'>
  <div class='row d-flex justify-content-center maindiv gx-0 overflow-auto' style="height: 88vh;">
    <div class="row d-flex ms-5 gx-0 mt-5 justify-content-start ">
      <div class="col-1 d-flex justify-content-start">
        <a href="<?php echo SITE_URL; ?>PHPOPS/Application/Admin/pgs.php" type="button" class="btn btn-outline-primary border border-3 border-primary ">
          < Back</a>
      </div>
    </div>
    <div class="col-8 d- justify-content-center">
      <form class="row ms-3 mt-5 p-2 border border-3 border-primary" method="POST" enctype="multipart/form-data">
        <div class="row  d-flex-row justify-content-center ">
          <div class='col-md-6'>
            <div>
              <img src="<?php if (isset($_GET['id'])) {
                          echo "https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg" . $getid . "/";
                          $photos =  explode(",", $upddata['Photos']);
                          echo $photos[0];
                        } else {
                          echo "https://cdn4.iconfinder.com/data/icons/social-communication/142/add_photo-512.png";
                        } ?>" alt="home image" id='profileimg' class="w-100 border border-2 p-2 img-fluid" srcset="">
            </div>
            <div class='d-flex justify-content-center mt-2 gap-4'>
              <?php
              foreach ($photos as $key => $value) {
                echo "<img id='profilesrc$key' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg" . $getid . "/" . $value . "' alt='home image' class='profilesrc border border-2 p-1 img-fluid' width='100px' height='100px'  srcset=''>";
              }
              ?>
            </div>
          </div>
          <div class="col-md-8">
            <label for="" class="form-label">Choose file</label>
            <input type="file" class="form-control" name="propertipic[]" id="propertipic" placeholder="" aria-describedby="proppic" multiple>
            <div id="proppic" class="form-text"></div>
          </div>
        </div>
        <div class="col-md-6">
          <label for="propname" class="form-label">Pg Name : </label>
          <input type="text" class="form-control" name="propname" id="propname" aria-describedby="Errpropname" value='<?php if (isset($_GET['id'])) {
                                                                                                                        echo $upddata['pg_name'];
                                                                                                                      } else {
                                                                                                                        echo $_POST['propname'];
                                                                                                                      } ?>'>
          <small id="Errpropname" class="form-text text-muted "></small>
        </div>
        <div class="col-md-6">
          <label for="shortdesc" class="form-label">Short Description: </label>
          <input type="text" class="form-control" id="shortdesc" name="shortdesc" aria-describedby="Errshortdesc" value='<?php if (isset($_GET['id'])) {
                                                                                                                            echo $upddata['sort_descreption'];
                                                                                                                          } else {
                                                                                                                            echo $_POST['shortdesc'];
                                                                                                                          } ?>'>
          <small id="Errshortdesc" class="form-text text-muted "></small>
        </div>
        <label for="longdesc">Long Description : </label>
        <div class="form-floating mt-2">
          <textarea class="form-control" placeholder="" id="longdesc" name="longdesc" aria-describedby="Errlongdesc" style="height: 100px"><?php if (isset($_GET['id'])) {
                                                                                                                                              echo $upddata['long_discription'];
                                                                                                                                            } else {
                                                                                                                                              echo trim($_POST['longdesc']);
                                                                                                                                            }; ?></textarea>
          <small id="Errlongdesc" class="form-text text-muted "></small>
        </div>
        <!-- <div class="col-md-12 mt-3">
            <label for="country" class="form-label">Features : </label>
            <input type="text"
                 class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
            </div> -->
        <div class="col-md-4 mt-3">
          <label for="country" class="form-label">Country</label>
          <select id="country" class="form-select" onchange='selectstate()' id='country' aria-describedby="Errcountry" name='country'>
            <option value='null'>Choose...</option>
            <?php
            $countrys = $con->select('country');

            foreach ($countrys as $key => $value) {
              echo "<option value='" . $value['country_id'] . "'";
              if (isset($_GET['id'])) {
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
        <div class="col-md-4 mt-3">
          <label for="state" class="form-label">State</label>
          <select id="state" class="form-select" onchange='selectcity()' value='' id='state' aria-describedby="Errstate" name='state'>
            <option value='null'>Choose...</option>
            <?php
            $states = $con->select('State');

            foreach ($states as $key => $value) {
              echo "<option value='" . $value['state_id'] . "'";
              if (isset($_GET['id'])) {
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
        <div class="col-md-4 mt-3">
          <label for="city" class="form-label">city : </label>
          <select id="city" class="form-select" value='' id='city' aria-describedby="Errcity" name='city'>
            <option value='null'>Choose...</option>
            <?php
            $citys = $con->select('city');
            foreach ($citys as $key => $value) {
              echo "<option value='" . $value['city_id'] . "'";
              if (isset($_GET['id'])) {
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
          <input type="text" class="form-control" id="Address" name="str_address" aria-describedby="Erraddress" placeholder="1234 Main St" value="<?php if (isset($_GET['id'])) {
                                                                                                                                                    echo $upddata['local_address'];
                                                                                                                                                  } else {
                                                                                                                                                    echo $_POST['str_address'];
                                                                                                                                                  }; ?>">
          <small id="Erraddress" class="form-text text-muted "></small>

        </div>
        <div class="col-md-4 mt-3">
          <label for="inputZip" class="form-label">Zip :</label>
          <input type="text" class="form-control" id="Zip" name="Zip" maxlength="6" aria-describedby="Errzip" value="<?php if (isset($_GET['id'])) {
                                                                                                                        echo $upddata['postcode'];
                                                                                                                      } else {
                                                                                                                        echo $_POST['Zip'];
                                                                                                                      } ?>">
          <small id="Errzip" class="form-text text-muted "></small>

        </div>
        <div class="col-md-4 mt-3">
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
              if (isset($_GET['id'])) {
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
        <div class="col-12 mt-2 d-flex gap-4" id='pgrooms'>
          <div class='col-md-4'>
            <label for="pggender" class="form-label">Gender : </label>
            <select id="pggender" class="form-select" value='' aria-describedby="Errpggender" name='pggender'>
              <option value='null'>Choose...</option>
              <option value="Boys" <?php if (isset($_GET['id'])) {
                                      if ($upddata['type'] == "Boys") {
                                        echo 'selected';
                                      }
                                    } else {
                                      echo $_POST['pggender'];
                                    } ?>>Boys</option>
              <option value="Girls" <?php if (isset($_GET['id'])) {
                                      if ($upddata['type'] == "Girls") {
                                        echo 'selected';
                                      }
                                    } else {
                                      echo $_POST['pggender'];
                                    } ?>>Girls</option>
              <option value="Both" <?php if (isset($_GET['id'])) {
                                      if ($upddata['type'] == "Both") {
                                        echo 'selected';
                                      }
                                    } else {
                                      echo $_POST['pggender'];
                                    } ?>>Both</option>
            </select>
            <small id="Errpggender" class="form-text text-muted"></small>
          </div>
          <div class='col-md-3'>
            <label for="pgrooms" class="form-label">Room Type : </label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="privateSharing" name="pgRoom[]" id="privateRoom" <?php if (isset($_GET['id'])) {
                                                                                                                        if (in_array("privateSharing", $Room_capacity)) {
                                                                                                                          echo 'checked';
                                                                                                                        }
                                                                                                                      } else {
                                                                                                                        echo $_POST['Room_capacity'];
                                                                                                                      } ?>>
              <label class="form-check-label" for="privateRoom">
                private room
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="doubleSharing" name="pgRoom[]" id="doubleRoom" <?php if (isset($_GET['id'])) {
                                                                                                                      if (in_array("doubleSharing", $Room_capacity)) {
                                                                                                                        echo 'checked';
                                                                                                                      }
                                                                                                                    } else {
                                                                                                                      echo $_POST['Room_capacity'];
                                                                                                                    } ?>>
              <label class="form-check-label" for="doubleRoom">
                Double Sharing
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="tripleSharing" name="pgRoom[]" id="tripleRoom" <?php if (isset($_GET['id'])) {
                                                                                                                      if (in_array("tripleSharing", $Room_capacity)) {
                                                                                                                        echo 'checked';
                                                                                                                      }
                                                                                                                    } else {
                                                                                                                      echo $_POST['Room_capacity'];
                                                                                                                    } ?>>
              <label class="form-check-label" for="tripleRoom">
                Triple Sharing
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="3+sharing" name="pgRoom[]" id="3+sharing" <?php if (isset($_GET['id'])) {
                                                                                                                  if (in_array("3+sharing", $Room_capacity)) {
                                                                                                                    echo 'checked';
                                                                                                                  }
                                                                                                                } else {
                                                                                                                  echo $_POST['Room_capacity'];
                                                                                                                } ?>>
              <label class="form-check-label" for="3+sharing">
                3+ Sharing
              </label>
            </div>
            <small id="Errpggender" class="form-text text-muted"></small>
          </div>
          <div class='col-md-3'>
            <label for="pgrooms" class="form-label">Facilities : </label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="AcRooms" name="facilities[]" id="acrooms" <?php if (isset($_GET['id'])) {
                                                                                                                  if (in_array("AcRooms", $facilities)) {
                                                                                                                    echo 'checked';
                                                                                                                  }
                                                                                                                } else {
                                                                                                                  echo $_POST['Room_capacity'];
                                                                                                                } ?>>
              <label class="form-check-label" for="acrooms">
                Ac rooms
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="food" name="facilities[]" id="food" <?php if (isset($_GET['id'])) {
                                                                                                            if (in_array("food", $facilities)) {
                                                                                                              echo 'checked';
                                                                                                            }
                                                                                                          } else {
                                                                                                            echo $_POST['Room_capacity'];
                                                                                                          } ?>>
              <label class="form-check-label" for="food">
                Food Included
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="wifi" name="facilities[]" id="wifi" <?php if (isset($_GET['id'])) {
                                                                                                            if (in_array("wifi", $facilities)) {
                                                                                                              echo 'checked';
                                                                                                            }
                                                                                                          } else {
                                                                                                            echo $_POST['Room_capacity'];
                                                                                                          } ?>>
              <label class="form-check-label" for="wifi">
                Wifi Available
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="wardrobe" name="facilities[]" id="wardrobe" <?php if (isset($_GET['id'])) {
                                                                                                                    if (in_array("wardrobe", $facilities)) {
                                                                                                                      echo 'checked';
                                                                                                                    }
                                                                                                                  } else {
                                                                                                                    echo $_POST['Room_capacity'];
                                                                                                                  } ?>>
              <label class="form-check-label" for="wardrobe">
                wardrobe
              </label>
            </div>
            <small id="Errpggender" class="form-text text-muted"></small>
          </div>


        </div>

        <div class="col-4 mt-3">
          <label for="price" class="form-label">Price :</label>
          <input type="number" class="form-control" id="price" name="price" aria-describedby="Errprice" value="<?php if (isset($_GET['id'])) {
                                                                                                                  echo $upddata['price'];
                                                                                                                } else {
                                                                                                                  echo $_POST['price'];
                                                                                                                } ?>" placeholder="Starts with .. ">
          <small id="Errprice" class="form-text text-muted "></small>
        </div>
        <div class="col-md-4 mt-3">
          <label for="status" class="form-label">Status : </label>
          <select id="status" class="form-select" value='' aria-describedby="Errstatus" name='status'>
            <option value='null'>Choose...</option>
            <option value="available" <?php if (isset($_GET['id'])) {
                                        if ($upddata['status'] == 'available') {
                                          echo 'selected';
                                        }
                                      } else if ($_POST['status'] == 'available') {
                                        echo 'selected';
                                      } ?>>Available</option>
            <option value="unavailable" <?php if (isset($_GET['id'])) {
                                          if ($upddata['status'] == 'unavailable') {
                                            echo 'selected';
                                          }
                                        } else if ($_POST['status'] == 'unavailable') {
                                          echo 'selected';
                                        } ?>>Unavailable</option>
          </select>
          <small id="Errstatus" class="form-text text-muted"></small>
        </div>
        <div class="col-4 mt-3">
          <label for="OwnerId" class="form-label">Owner : </label>
          <select id="OwnerId" class="form-select" aria-describedby="Errowner" name='OwnerId'>
            <option  value='null'><b>Choose..</b></option>
            <?php
            $owner_names = $con->select("Users");
            foreach ($owner_names as $key => $value) {
              echo "<option value='" . $value['user_id'] . "'";
              if ($upddata['Owner_Id'] == $value['user_id']) {
                echo 'selected';
              }
              echo ">" . $value['name'] . "</option>";
            }
            ?>
          </select>
          <small id="Errowner" class="form-text text-muted "></small>
        </div>
        <div class="col-12 mt-3 d-flex gap-3 justify-content-center ">
          <button type="submit" class="btn btn-danger" id="AddPg" name='AddPg'>Done</button>
          <!-- <button type="reset" class="btn btn-">Reset</button> -->
        </div>
      </form>
    </div>
  </div>
</div>
<script src="adminscript.js"></script>
<script>
  $(document).ready(function() {
    $(document).on('change', "#propertipic", (e) => {
      var src = URL.createObjectURL(e.target.files[0])
      $("#profileimg").attr("src", src);
    })
  });

  $(document).on('click', ".profilesrc", function() {
    $("#profileimg").attr("src", this.src);
  });
</script>
<?php
require_once 'lib/footer.php';
