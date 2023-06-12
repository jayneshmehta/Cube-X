<?php
session_start();
require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';

$con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
// $con = new Operations('localhost','root','','employees_dbms');

if (isset($_GET['id'])) {
  if (!isset($_SESSION['useremail']) && !isset($_SESSION['userpassword'])) {
    header("Location: index.php");
    exit;
  }
  $userid = (int)base64_decode($_GET['id']);
  $userdata = $con->select("Users", "*", null, null, "user_id", $userid);
  $userdata = $userdata[0];
  if (empty($userdata)) {
    echo "No Data Found";
    exit;
  }
  $title = "Update User : ";
} else {
  $title = "Register User";
  if (isset($_SESSION['useremail']) && isset($_SESSION['userpassword'])) {
    header("Location: index.php");
    exit;
  }
}

require_once '../lib/siteConstant.php';
require_once '../lib/site_variables.php';
require_once '../vendor/autoload.php';
require_once '../lib/Aws.php';
require_once 'sendmail.php';

$otp = genetateotp();

$objAws = new Aws($_AWS_S3_CREDENTIALS);
require_once './Ajaxcalls.php';

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $Regemail = $_POST['Regemail'];
  $Regpassword = $_POST['Regpassword'];
  $gender = $_POST['gender'];
  $contact = $_POST['contact'];
  $country = $_POST['country'];
  $state = $_POST['state'];
  $city = $_POST['city'];
  $str_address = $_POST['str_address'];
  $Zip = $_POST['postcode'];
  $type = $_POST['type'];
  $filename = $_FILES['profile_pic']['name'];
  $tmpfile = $_FILES['profile_pic']['tmp_name'];
  $emailflag = true;

  $email = $con->select("Users", "*", null, null, "email", $Regemail);
  if (isset($_GET['id'])) {
    if ($email['email'] == $Regemail || empty($email)) {
      $emailflag = false;
      $error = "Email alreay exist .. !";
    }
  } else {
    if (!empty($email)) {
      $emailflag = false;
      $error = "Email alreay exist .. !";
    }
  }
  if ($emailflag) {
    $imgtype = array("image/jpeg", "image/png", "image/jpg", "");
    if (!in_array($_FILES['profile_pic']['type'], $imgtype)) {
      $error = "Image should be in jpeg/png/jpg Formate only..";
    } else {

      if ($_FILES['profile_pic']['name'] == "") {
        $filename = $userdata['profile_pic'];
        if ($userdata['profile_pic'] == "") {
          $filename = "nopic";
        }
      }

      $col_values = array($name, $Regemail, $gender, $Regpassword, $str_address, $country, $state, $city, $Zip, $contact, $filename, $type);
      $cols = array('name', 'email', 'gender', 'password', 'current_address', 'country', 'state', 'city', 'postcode', 'contact_no', 'profile_pic', 'user_type');

      // $con->insert('Users',$cols,$values);
      if (isset($_GET['id'])) {

        $storefilename = "user" . $userid;
        if (file_exists("Admin/Files/images/$storefilename/")) {
          if ($_FILES['profile_pic']['name'] != null) {
            try {
              if($userdata['profile_pic'] != "nopic"){
                unlink("Admin/Files/images/$storefilename/" . $userdata['profile_pic']);
                $objAws->deletefile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $userdata['profile_pic']);
              }
              // rmdir("Files/images/$storefilename/");
              move_uploaded_file($tmpfile, "Admin/Files/images/$storefilename/" . $filename);
              $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $filename);
            } catch (Exception $e) {
              echo "Error in Uploading images";
            }
          }
        } else {
          try {
            mkdir("Admin/Files/images/$storefilename/", 0777, true);
            move_uploaded_file($tmpfile, "Admin/Files/images/$storefilename/" . $filename);
            $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $filename);
          } catch (Exception $e) {
            echo "Error in uploading profile images..";
          }
        }
        try {
          $con->update("Users", $cols, $col_values, "user_id", $userid);
        } catch (Exception $e) {
          echo "Error in updating data into database..";
        }
        $_SESSION['updateeduser'] = "done";
        header("Location: userinfo.php");
      } else {
        $_SESSION['cols'] = $cols;
        $_SESSION['col_values'] = $col_values;
        $_SESSION['temp_name'] = $tmpfile;
        $_SESSION['otp'] = $otp;
        $_SESSION['Regemail'] = $Regemail;
        sendmail($Regemail, $otp, $name);
        move_uploaded_file($tmpfile, "Admin/Files/tempimg/" . $filename);
        header("Location: Registeruser.php?action=verifyOTP");
        // unlink("Admin/Files/tempimg/$filename");
        // exit;
      }
    }
  }
}

require_once 'userlib/header.php';
require_once 'userlib/navbar.php';
?>

<div class='row d-flex justify-content-center gx-0'>
  <div class="row d-flex justify-content-center">
    <div class='col-7'>
      <p class='text-success fs-3 mt-4'><?php echo $title ?></p>
    </div>
  </div>
  <div class="col-6 d-flex justify-content-center ">
    <form id='form' class="shadow g-3 bg-light mt-5 p-4 row  rounded" method='POST' enctype="multipart/form-data">
      <div class='col-12 d-flex justify-content-center'>
        <div class="col-3 mx-auto">
          <div class=' border border-2 border-dark p-2 justify-content-center rounded'>
            <img class="w-100" id='profilesrc' src=<?php if ($_GET['id'] && $userdata['profile_pic'] != "nopic") {
                                                      echo "'https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user" . $userid . "/" . $userdata['profile_pic'] . "'";
                                                    } else {
                                                      echo "'https://cdn.pixabay.com/photo/2015/03/04/22/35/avatar-659651_640.png'";
                                                    } ?> id="profile" alt="logo">
          </div>
          <div class="mb-3 mt-3">
            <input type="file" class="form-control" name="profile_pic" id="profile_pic" placeholder="" aria-describedby="fileinput">
            <small id="profile_picErr" class="form-text tjayneshext-muted"></small>
          </div>
        </div>

      </div>
      <div class="col-4">
        <label for="type" class="form-label">Who are you : </label>
        <select id="type" class="form-select " aria-describedby="typeErr" name="type">
          <option value="null">Choose...</option>
          <option value="owner" <?php if (isset($_GET['id'])) {
                                  if ($userdata['user_type'] == 'owner') {
                                    echo 'selected';
                                  }
                                } else if ($_POST['type'] == 'owner') {
                                  echo 'selected';
                                } ?>>Owner</option>
          <option value="rentel" <?php if (isset($_GET['id'])) {
                                    if ($userdata['user_type'] == 'rentel') {
                                      echo 'selected';
                                    }
                                  } else if ($_POST['type'] == 'rentel') {
                                    echo 'selected';
                                  } ?>>Rentel</option>
        </select>
        <small id="typeErr" class="form-text text-muted"></small>
      </div>
      <div class="col-4">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" aria-describedby="nameErr" name="name" id="name" value='<?php if (isset($_GET['id'])) {
                                                                                                          echo $userdata['name'];
                                                                                                        } else {
                                                                                                          echo $_POST['name'];
                                                                                                        } ?>'>
        <small id="nameErr" class="form-text text-muted"></small>
      </div>
      <div class="col-4">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" aria-describedby="RegemailErr" name="Regemail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" id="Regemail" value='<?php if (isset($_GET['id'])) {
                                                                                                                                                                          echo $userdata['email'];
                                                                                                                                                                        } else {
                                                                                                                                                                          echo $_POST['Regemail'];
                                                                                                                                                                        } ?>'>
        <small id="RegemailErr" class="form-text text-muted"></small>
      </div>
      <div class="col-md-6">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" aria-describedby="RegpasswordErr" name="Regpassword" id="Regpassword" value='<?php if (isset($_GET['id'])) {
                                                                                                                                    echo $userdata['password'];
                                                                                                                                  } else {
                                                                                                                                    echo $_POST['Regpassword'];
                                                                                                                                  } ?>'>
        <small id="RegpasswordErr" class="form-text text-muted"></small>
      </div>
      <div class="col-md-6">
        <label for="Gender" class="form-label">Gender</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" value='F' name="gender" id="female" <?php if (isset($_GET['id'])) {
                                                                                              if ($userdata['gender'] == 'F') {
                                                                                                echo 'checked';
                                                                                              }
                                                                                            } else if ($_POST['gender'] == 'F') {
                                                                                              echo 'checked';
                                                                                            } ?>>
          <label class="form-check-label" for="female">
            Female
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" value='M' name="gender" id="male" <?php if (isset($_GET['id'])) {
                                                                                            if ($userdata['gender'] == 'M') {
                                                                                              echo 'checked';
                                                                                            }
                                                                                          } else if ($_POST['gender'] == 'M') {
                                                                                            echo 'checked';
                                                                                          }  ?>>
          <label class="form-check-label" for="male">
            Male
          </label>
          <small id="genderErr" class="form-text text-muted"></small>
        </div>
      </div>

      <div class="col-4">
        <label for="contact" class="form-label">Contact No: </label>
        <input type="number" class="form-control" id="contact" aria-describedby="contactErr" name="contact" placeholder="" pattern="/^[0-9]{10}$^/" value='<?php if (isset($_GET['id'])) {
                                                                                                                                                              echo $userdata['contact_no'];
                                                                                                                                                            } else {
                                                                                                                                                              echo $_POST['contact'];
                                                                                                                                                            } ?>'>
        <small id="contactErr" class="form-text text-muted"></small>
      </div>

      <div class="col-md-4">
        <label for="country" class="form-label">Country</label>
        <select id="country" class="form-select" aria-describedby="country" onchange='selectstate()' name="country">
          <option value="null">Choose...</option>
          <?php
          $country = $con->select('country');
          foreach ($country as $key => $value) {
            echo "<option value='" . $value['country_id'] . "'";
            if (isset($_GET['id'])) {
              if ($userdata['country'] == $value['country_id']) {
                echo 'selected';
              }
            } else if ($_POST['country'] == $value['country_id']) {
              echo 'selected';
            }
            echo ">" . $value['country_name'] . "</option>";
          }
          ?>
        </select>
        <small id="countryErr" class="form-text text-muted"></small>
      </div>

      <div class="col-md-4">
        <label for="state" class="form-label">State</label>
        <select id="state" class="form-select " aria-describedby="state" onchange='selectcity()' name="state">

          <?php
          $state = $con->select('State');
          echo "<option value='null'>Choose</option>";
          foreach ($state as $key => $value) {
            echo "<option value='" . $value['state_id'] . "'";
            if (isset($_GET['id'])) {
              if ($userdata['state'] == $value['state_id']) {
                echo 'selected';
              }
            } else if ($_POST['state'] == $value['state_id']) {
              echo 'selected';
            }
            echo " >" . $value['state_name'] . " </option>";
          }
          ?>
        </select>
        <small id="stateErr" class="form-text text-muted"></small>
      </div>
      <div class="col-md-4">
        <label for="city" class="form-label">City</label>
        <select id="city" class="form-select" aria-describedby="city" name='city'>
          <option value="null">Choose...</option>
          <?php
          $city = $con->select('city');
          foreach ($city as $key => $value) {
            echo "<option value='" . $value['city_id'] . "'";
            if (isset($_GET['id'])) {
              if ($userdata['city'] == $value['city_id']) {
                echo 'selected';
              }
            } else if ($_POST['city'] == $value['city_id']) {
              echo 'selected';
            }
            echo " >" . $value['city_name'] . " </option>";
          }
          ?>
        </select>
        <small id="cityErr" class="form-text text-muted"></small>
      </div>
      <div class="col-5">
        <label for="str_address" class="form-label">Street Address</label>
        <input type="text" class="form-control" id="str_address" aria-describedby="ErrAdd" name="str_address" placeholder="" value='<?php if (isset($_GET['id'])) {
                                                                                                                                      echo $userdata['current_address'];
                                                                                                                                    } else {
                                                                                                                                      echo $_POST['str_address'];
                                                                                                                                    } ?>'>
        <small id="ErrAdd" class="form-text text-muted"></small>
      </div>
      <div class="col-md-3">
        <label for="Zip" class="form-label">Zip</label>
        <input type="number" class="form-control" id="Zip" aria-describedby="Errzip" name="postcode" value='<?php if (isset($_GET['id'])) {
                                                                                                              echo $userdata['postcode'];
                                                                                                            } else {
                                                                                                              echo $_POST['postcode'];
                                                                                                            } ?>'>
        <small id="Errzip" class="form-text text-muted"></small>
      </div>
      <div class="col-12 d-flex justify-content-center">
        <button type="submit" name='submit' id='Regsubmit' data-bs-toggle="modal" value='submit' class="btn btn-primary">Submit</button>

      </div>
    </form>
  </div>
  <div class="d-flex justify-content-center text-danger fs-3">
    <?php
    echo $error;
    ?>
  </div>

  <!-- modal -->
  <div class="modal fade <?php echo $_GET['action'] == "verifyOTP" ? "show" : ""; ?>" id="usersignin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 text-success" id="userloginmodaltitle">OTP verification : </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post">
            <div class="mb-3">
              <p id='verifyEmail'>OTP has send to : <span class='text-primary'> <?php echo $_SESSION['Regemail']; ?></span></p>
              <input type="number" class="form-control" name="otp" id="otp" aria-describedby="otpverifyErr" placeholder="Enter OTP">
              <small id="otpverifyErr" class="form-text text-muted "></small>
            </div>
            <p>Didn't received any OTP ? <a class='text-danger' id='resendotp'>Resend OTP</a></p>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success " id="usersigninbtn">Signin </button>
        </div>
        <div id='showErrorinSendotp' class='px-5'>
        </div>
      </div>
    </div>
  </div>

  <?php
  if ($_GET['action'] == "verifyOTP") {
    echo "<script>
      $(window).on('load', function() {
        $('#usersignin').modal('show');
      });
      </script>";
  }
  ?>

  <script>
    <?php echo $script; ?>
  </script>
  <script src='userscript.js'>
  </script>
  <?php
  require_once 'userlib/footer.php';
  ?>