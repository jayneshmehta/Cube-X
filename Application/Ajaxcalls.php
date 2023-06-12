<?php

//toaster on log in user has login or not
// if ($_POST['action'] == 'checklogin') {
//   if (isset($_SESSION['useremail']) && isset($_SESSION['userpassword'])) {
//     echo "success";
//   } else {
//     echo 'failed';
//   }
//   exit;
// }

if($_SESSION['Successfulllogin'] == 'done'){
  $loginmsg =  " toastr.success('Login successfull...')";
  unset($_SESSION['Successfulllogin']);
}
if($_SESSION['SuccessfullLogout'] == 'done'){
  $logoutmsg =  " toastr.success('Logout successfull...')";
  unset($_SESSION['SuccessfullLogout']);
}

//send OTP for forget Password
if ($_POST['action'] == 'sendFpOtp') {
  $email = $_POST['email'];
  $data = $con->select('Users', "*", null, null, 'email', $email);
  $data = $data[0];

  //OTP in session to check further
  if ($data != null) {
    $_SESSION['otp'] = $otp;
    $_SESSION['sendotpemail'] = $email;
    $_SESSION['OTPcreatetime'] = time();
    $error = 'success';
    //sent sms;
    try {
      sendsms($data['contact_no'],$data['name'],$otp);
    } catch (Exception $e) {
      $error =  "Error in sending SMS otp";
    }

    try {
      sendmail($email, $otp, $data['name']);
    } catch (Exception $e) {
      $error =  "Error in sending Email otp";
    }
    echo $error;
  } else {
    echo 'failed';
  }
  exit;
}

//Check OTP On Forget Password on sms/Mail
//check otp and otp set in session 
if ($_POST['action'] == 'checkFpOtp') {
  $otp = $_POST['otp'];
  $email = $_POST['email'];
  if ($_SESSION['sendotpemail'] == $email) {
    if ($otp == $_SESSION['otp']) {
      $_SESSION['changePasswordEmail'] = $email;
      echo 'success';
      unset($_SESSION['sendotpemail']);
      unset($_SESSION['otp']);
    } else {
      echo 'failed';
    }
  } else {
    echo "wrongemail";
  }
  exit;
}

//Change the password in Db.
if ($_POST['action'] == 'changepassword') {
  $newpassword = $_POST['password'];
  $email = $_SESSION['changePasswordEmail'];
  $con->update("Users", ["password"], [$newpassword], 'email', $email);
  unset($_SESSION['changePasswordEmail']);
  echo 'success';
  exit;
}

//User login 
if ($_POST['action'] == 'userlogin') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $rememberme = $_POST['rememberme'];
  $auth = $con->select('Users');

  $flag = false;
  foreach ($auth as $key => $value) {
    if ($value['email'] == $email && $value['password'] == $password) {
      $flag = true;
      $img = $value['profile_pic'];
      $userid = $value['user_id'];
      setcookie('profile_pic', $img, time() + (3600 * 24), "/");
      setcookie('user_id', $userid, time() + (3600 * 24), "/");
      if ($rememberme == 'on') {
        setcookie('useremail', $email, time() + (3600 * 24), "/");
        setcookie('userpassword', $password, time() + (3600 * 24), "/");
      }
      break;
    }
  }

  if (!$flag) {
    echo "Failed";
  } else {
    $_SESSION['useremail'] = $email;
    $_SESSION['userpassword'] = $password;
    $_SESSION['Successfulllogin'] = 'done';
    echo 'success';
  }
  exit;
}

//show state on select of country..
if ($_POST['action'] == 'selectstate') {
  $country = $_POST['country'];
  $result = $con->select(array("State", 'country'), array('state_name', 'state_id'), "right join", array('country_id', 'country_id'), "B.country_id", $country);
  $result = json_encode($result);
  print_r($result);
  exit;
}

//show city on select of state..
if ($_POST['action'] == 'selectcity') {
  $state = $_POST['state'];
  $result = $con->select(array("State", 'city'), array('city_name', 'city_id'), "right join", array('state_id', 'state_id'), "B.state_id", $state);
  $result = json_encode($result);
  print_r($result);
  exit;
}

//Property Contact owner 
if ($_POST['action'] == 'porpertycontact_detail') {
  $propid = $_POST['propid'];
  $ownerdata = $con->select(array('properties', 'Users'), '*', "right join", array("Owner_id", "user_id"), "property_id", $propid);
  $ownerdata = $ownerdata[0];
  print_r(json_encode($ownerdata));
  exit;
}

// PG contact details
if ($_POST['action'] == 'pgcontact_detail') {
  $propid = $_POST['propid'];
  $ownerdata = $con->select(array('pgtable', 'Users'), '*', "left join", array("Owner_id", "user_id"), "pg_id", $propid);
  $ownerdata = $ownerdata[0];
  $ownerdata['name'] = ucfirst($ownerdata['name']);
  if ($ownerdata['user_id'] != null) {
    print_r(json_encode($ownerdata));
  } else {
    echo 'null';
  }
  exit;
}

//rating for Properties
if ($_POST['action'] == 'propertyratings') {
  $rating = $_POST['rating'];
  $propID =  $propid;
  $userId = $_SESSION['useremail'];

  $ratings = ['user_id' => $userId, 'rating' => $rating];
  $ratingdata = json_encode($ratings);
  $result = $con->select("properties", "Rating", null, null, "property_id", $propID);
  $convertJson;
  $dbjson =  json_decode($result[0]['Rating'], true);

  $flag = true;
  foreach ($dbjson as $key => $value) {
    if ($value['user_id'] == $userId) {
      $dbjson[$key]['rating'] = $rating;
      $convertJson = json_encode($dbjson);
      $flag = false;
      break;
    }
  }

  if (empty($result[0]['Rating'])) {
    $result[0]['Rating'] = [$ratings];
    $convertJson = json_encode($result[0]['Rating']);
  } else {
    if ($flag) {
      $getrating = json_decode($result[0]['Rating'], true);
      array_push($getrating, $ratings);
      $convertJson = json_encode($getrating);
    }
  }
  try {
    $con->update("properties", ['Rating'], [$convertJson], "property_id", $propID);
  } catch (Exception $e) {
    echo "unable to update into db";
  }
  exit;
}

//Rating for PGs
if ($_POST['action'] == 'pgratings') {
  $rating = $_POST['rating'];
  $propID =  $propid;
  $userId = $_SESSION['useremail'];

  $ratings = ['user_id' => $userId, 'rating' => $rating];
  $ratingdata = json_encode($ratings);
  $result = $con->select("pgtable", "Rating", null, null, "pg_id", $propID);

  $convertJson;

  $dbjson =  json_decode($result[0]['Rating'], true);


  $flag = true;
  foreach ($dbjson as $key => $value) {
    if ($value['user_id'] == $userId) {
      $dbjson[$key]['rating'] = $rating;
      $convertJson = json_encode($dbjson);
      $flag = false;
      break;
    }
  }
  if (empty($result[0]['Rating'])) {
    $result[0]['Rating'] = [$ratings];
    $convertJson = json_encode($result[0]['Rating']);
  } else {
    if ($flag) {
      $getrating = json_decode($result[0]['Rating'], true);
      array_push($getrating, $ratings);
      $convertJson = json_encode($getrating);
    }
  }
  try {
    $con->update("pgtable", ['Rating'], [$convertJson], "pg_id", $propID);
  } catch (Exception $e) {
    echo "Unable to update to pg.. ";
  }
  exit;
}

// Resend OTP on new user verification..
if ($_POST['action'] == 'resendotp') {
  $otp = genetateotp();
  $Regemail = $_SESSION['Regemail'];
  $mail = sendmail($Regemail, $otp, $name);
  $_SESSION['otp'] = $otp;
  $_SESSION['setotpTime'] = time();
  echo 'Done';
  exit;
}

//Verify OTP on new user register ..
if ($_POST['action'] == 'verifyotp') {
  $reciveotp = $_POST['otp'];
  // echo $_SESSION['otp'];
  $inactive = 600;
  
  $session_life = time() - $_SESSION['setotpTime'];
  if ($session_life > $inactive) {
    if (isset($_SESSION['setotpTime'])) {
      unset($_SESSION['otp']);
    }
  }
  if ($reciveotp == $_SESSION['otp']) {

    $id =  $con->insert("Users", $_SESSION['cols'], $_SESSION['col_values']);
    $newuser =  $con->select("Users", '*', null, null, 'user_id', $id);
    $filename = $newuser[0]['profile_pic'];
    $templocation = "Admin/Files/tempimg/$filename";
    $storefilename = "user" . $id;
    if (!file_exists("Admin/Files/images/$storefilename/")) {
      if (file_exists($templocation)) {
        mkdir("Admin/Files/images/$storefilename/", 0777, true);
        copy($templocation, "Admin/Files/images/$storefilename/" . $filename);
        unlink($templocation);
        $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $filename);
      } else {
        rmdir("Admin/Files/images/$storefilename");
      }
    }
    $_SESSION['Signin'] = "SignSuccessfull";
    unset($_SESSION['otp']);
    echo "success";
  } else {
    echo "failed";
  }
  exit;
}


//userinfo page delete Property..
if ($_POST['action'] == 'delProp') {
  $delid = $_POST['delid'];

  $objAws = new Aws($_AWS_S3_CREDENTIALS);

  $prop_pic = $con->select("properties", 'Photos', null, null, "property_id", $delid);

  $prop_pic = $prop_pic[0]['Photos'];
  $prop_pic = explode(",", $prop_pic);

  $foldername = 'property' . $delid;
  foreach ($prop_pic as $key => $value) {
    unlink("Admin/Files/propertyImg/$foldername/" . $value);
    $objAws->deletefile($_AWS_S3_CREDENTIALS, "propertyImg/" . $foldername . "/" . $value);
  }
  rmdir("Admin/Files/propertyImg/$foldername");
  $objAws->deletefile($_AWS_S3_CREDENTIALS, "propertyImg/" . $foldername);
  $con->delete("properties", "property_id", $delid);
  exit;
}


//userinfo page delete PG..
if ($_POST['action'] == 'delPg') {
  $delid = $_POST['delid'];

  $objAws = new Aws($_AWS_S3_CREDENTIALS);

  $prop_pic = $con->select("pgtable", 'Photos', null, null, "pg_id", $delid);

  $prop_pic = $prop_pic[0]['Photos'];
  $prop_pic = explode(",", $prop_pic);

  $foldername = 'pg' . $delid;
  foreach ($prop_pic as $key => $value) {
    unlink("Admin/Files/Pgimages/$foldername/" . $value);
    $objAws->deletefile($_AWS_S3_CREDENTIALS, "Pgimages/" . $foldername . "/" . $value);
  }
  rmdir("Admin/Files/Pgimages/$foldername");
  $objAws->deletefile($_AWS_S3_CREDENTIALS, "Pgimages/" . $foldername);
  $con->delete("pgtable", "pg_id", $delid);
  echo 'success';
  exit;
}

//showproperty page  for searching/filtering properties.. 
if ($_POST['action'] == 'showlistforproperty') {

  $rooms = $_POST['rooms'];
  $type = $_POST['type'];
  $location = $_POST['location'];
  $catagory = $_POST['catagory'];
  $searchele = $_POST['searchele'];
  $price = $_POST['price'];
  $list = $con->select('properties');

  $prices =  array_column($list, "price");
  if ($price == 'L2H') {
    array_multisort($prices, SORT_ASC, $list);
  } else {
    array_multisort($prices, SORT_DESC, $list);
  }
  $data = [];
  foreach ($list as $key => $value) {
    $state = $con->select('State', "*", null, null, 'state_id', $value['state']);
    $state = $state[0]['state_name'];

    $country = $con->select("country", array('country_name'), null, null, "country_id", $value['country']);
    $country = $country[0]['country_name'];

    $city = $con->select("city", array('city_name'), null, null, "city_id", $value['city']);
    $city = $city[0]['city_name'];

    if (strpos(strtolower($value['property_name']), strtolower($searchele)) !== false || strpos(strtolower($state), strtolower($searchele)) !== false) {
      if ($rooms == $value['Rooms'] || $rooms == 'null') {
        if ($catagory == $value['catagories'] || $catagory == 'null') {
          if ($type == $value['type'] || $type == 'null') {
            if ($location == $value['city'] || $location == 'null') {
              $photos = explode(",", $value['Photos']);
              $state = $con->select('State', "*", null, null, 'state_id', $value['state']);
              $state = $state[0]['state_name'];

              $country = $con->select("country", array('country_name'), null, null, "country_id", $value['country']);
              $country = $country[0]['country_name'];

              $city = $con->select("city", array('city_name'), null, null, "city_id", $value['city']);
              $city = $city[0]['city_name'];

              $value['country'] = $country;
              $value['state'] = $state;
              $value['city'] = $city;

              $value['price'] = number_format($value['price']);
              array_push($data, $value);
            }
          }
        }
      }
    }
  }
  if (empty($data)) {
    exit;
  } else {
    print_r(json_encode($data));
  }
  exit;
}

//showpgs page  for searching/filtering PG's..
if ($_POST['action'] == 'showlistforPgs') {

  $rooms = $_POST['rooms'];
  $NumRooms = $_POST['NumRooms'];
  $location = $_POST['location'];
  $peference = $_POST['preference'];
  $searchele = $_POST['searchele'];
  $price = $_POST['price'];


  $list = $con->select('pgtable');

  $prices =  array_column($list, "price");
  if ($price == 'L2H') {
    array_multisort($prices, SORT_ASC, $list);
  } else {
    array_multisort($prices, SORT_DESC, $list);
  }
  $data = [];

  foreach ($list as $key => $value) {

    $state = $con->select('State', "*", null, null, 'state_id', $value['state']);
    $state = $state[0]['state_name'];

    $country = $con->select("country", array('country_name'), null, null, "country_id", $value['country']);
    $country = $country[0]['country_name'];

    $city = $con->select("city", array('city_name'), null, null, "city_id", $value['city']);
    $city = $city[0]['city_name'];

    $sharing = explode(",", $value['Room_capacity']);

    if (strpos(strtolower($value['pg_name']), strtolower($searchele)) !== false || strpos(strtolower($state), strtolower($searchele)) !== false) {
      if ($rooms == $value['Rooms'] || $rooms == 'null') {
        if ($peference == $value['type'] || $peference == 'null') {
          if (in_array($NumRooms, $sharing) || $NumRooms == 'null') {
            if ($location == $value['city'] || $location == 'null') {
              $state = $con->select('State', "*", null, null, 'state_id', $value['state']);
              $state = $state[0]['state_name'];

              $country = $con->select("country", array('country_name'), null, null, "country_id", $value['country']);
              $country = $country[0]['country_name'];

              $city = $con->select("city", array('city_name'), null, null, "city_id", $value['city']);
              $city = $city[0]['city_name'];

              $sharing = explode(",", $value['Room_capacity']);
              $photos = explode(",", $value['Photos']);

              $value['country'] = $country;
              $value['state'] = $state;
              $value['city'] = $city;

              $value['price'] = number_format($value['price']);
              array_push($data, $value);
            }
          }
        }
      }
    }
  }
  if (empty($data)) {
    exit;
  } else {
    print_r(json_encode($data));
  }
  exit;
}