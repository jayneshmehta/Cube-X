<?php
// login for admin
if ($_POST["action"] == 'adminlogin') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remembercheck = $_POST['rememberme'];
    $db = $con->select("admin");
    $flag = false;
    foreach ($db as $key => $value) {
        if ($value['Username'] == $username && $value['Password'] == $password) {
            $flag = true;
            if ($remembercheck == 'on') {
                setcookie('adminusername', $username, time() + (3600 * 24), "/");
                setcookie('adminpassword', $password, time() + (3600 * 24), "/");
                break;
            }
        }
    }
    if ($flag) {
        $_SESSION['adminUsername'] = $username;
        $_SESSION['adminPassword'] = $password;
        echo 'success';
    } else {
        echo 'failed';
    }
    exit;
}

//Admin Logout
if ($_POST['action'] == "adminlogout") {
    // echo $_SESSION['adminUsername'];
    unset($_SESSION['adminUsername']);
    unset($_SESSION['adminPassword']);
    echo 'success';
    exit;
}

//Select state on select of country 
if ($_POST['action'] == 'selectstate') {
    $country = $_POST['country'];
    $result = $con->select(array("State", 'country'), array('state_name', 'state_id'), "right join", array('country_id', 'country_id'), "B.country_id", $country);
    foreach ($result as $key => $value) {
        echo "<option value=" . $value['state_id'] . ">" . $value['state_name'] . "</option>";
    }
    exit;
}

//Select city on select state  
if ($_POST['action'] == 'selectcity') {
    $state = $_POST['state'];
    $result = $con->select(array("State", 'city'), array('city_name', 'city_id'), "right join", array('state_id', 'state_id'), "B.state_id", $state);
    foreach ($result as $key => $value) {
        echo "<option value=" . $value['city_id'] . ">" . $value['city_name'] . "</option>";
    }
    exit;
}

//Select state on select of country for user in add property new user
if ($_POST['action'] == 'userselectstate') {
    $country = $_POST['country'];
    $result = $con->select(array("State", 'country'), array('state_name', 'state_id'), "right join", array('country_id', 'country_id'), "B.country_id", $country);
    foreach ($result as $key => $value) {
        echo "<option value=" . $value['state_id'] . ">" . $value['state_name'] . "</option>";
    }
    exit;
}

//Select city on select of state for user in add property new user
if ($_POST['action'] == 'userselectcity') {
    $state = $_POST['state'];
    $result = $con->select(array("State", 'city'), array('city_name', 'city_id'), "right join", array('state_id', 'state_id'), "B.state_id", $state);
    foreach ($result as $key => $value) {
        echo "<option value=" . $value['city_id'] . ">" . $value['city_name'] . "</option>";
    }
    exit;
}

//Search in PG's
if ($_POST['action'] == 'SearchPG') {

    $searchprop = $_POST['searchprop'];
    $result = $con->select('pgtable');
    $catagorys = $_POST['catagory'];
    $i = 1;
    $data = [];
    foreach ($result as $key => $value) {

        $que =  $con->select(array("country", "pgtable"), "country_name", "right join", array("country_id", "country"), "pg_id", $value['pg_id']);
        $country = ($que[0]['country_name']);

        $state = $con->select(array("State", "pgtable"), "state_name", "right join", array("state_id", "state"), "state_id", $value['state']);
        $state = ($state[0]['state_name']);

        $city = $con->select(array("pgtable", "city"), "city_name", "right join", array("city", "city_id"), "city_id", $value['city']);
        $city = ($city[0]['city_name']);

        if (strpos(strtolower($value['pg_name']), strtolower($searchprop)) !== false || strpos(strtolower($country), strtolower($searchprop)) !== false ||  strpos(strtolower($city), strtolower($searchprop)) !== false  || strpos(strtolower($state), strtolower($searchprop)) !== false) {

            if ($value['type'] == $catagorys || $catagorys == 'null') {

                $userowner = $con->select(array("Users", "pgtable"), array("name", 'user_type'), "right join", array("user_id", "Owner_id"), "pg_id", $value['pg_id']);
                $usernameowner = ($userowner[0]['name']);
                $username = "<b>Owner : </b>$usernameowner";
                $photo = explode(",", $value['Photos']);
                $value['city'] = $city;
                $value['state'] = $state;
                $value['country'] = $country;
                $value['Owner_Id'] = $usernameowner;

                array_push($data, $value);
            }
        }
    }
    if (!empty($data)) {
        print_r(json_encode($data));
    }
    exit;
}

//Search in Properties
if ($_POST['action'] == 'searchingproperty') {
    $searchprop = $_POST['searchprop'];
    $result = $con->select('properties');
    $catagorys = $_POST['catagory'];

    $data = [];
    foreach ($result as $key => $value) {

        $catagory = $con->select(array('properties', 'catagory'), array("catagorie_id", "catagorie_name"), "right join", array('catagories', "catagorie_id"), "property_id", $value['property_id']);
        $catagoryid = $catagory[0]["catagorie_id"];
        $catagoryname = $catagory[0]["catagorie_name"];
        $que =  $con->select(array("country", "properties"), "country_name", "right join", array("country_id", "country"), "property_id", $value['property_id']);
        $country = ($que[0]['country_name']);

        $state = $con->select(array("State", "properties"), "state_name", "right join", array("state_id", "state"), "state_id", $value['state']);
        $state = ($state[0]['state_name']);

        $city = $con->select(array("properties", "city"), "city_name", "right join", array("city", "city_id"), "city_id", $value['city']);
        $city = ($city[0]['city_name']);

        if (strpos(strtolower($value['property_name']), strtolower($searchprop)) !== false || strpos(strtolower($country), strtolower($searchprop)) !== false ||  strpos(strtolower($city), strtolower($searchprop)) !== false  || strpos(strtolower($state), strtolower($searchprop)) !== false) {

            if ($catagoryid == $catagorys || $catagorys == 'null') {


                $userowner = $con->select(array("Users", "properties"), array("name", 'user_type'), "right join", array("user_id", "Owner_id"), "property_id", $value['property_id']);
                $usernameowner = ($userowner[0]['name']);
                $usertype = ($userowner[0]['user_type']);
                $userrental = $con->select(array("Users", "properties"), array("name", 'user_type'), "right join", array("user_id", "Rental_id"), "property_id", $value['property_id']);
                $usernamerental = ($userrental[0]['name']);
                $username = "<b>Owner : </b>$usernameowner";
                $username .= "<br><b>Rental : </b> $usernamerental";
                $value['city'] = $city;
                $value['state'] = $state;
                $value['country'] = $country;
                $value['Owner_Id'] = $usernameowner;
                $value['Rental_Id'] = $usernamerental;
                $value['catagories'] = $catagoryname;
                array_push($data, $value);
            }
        }
    }
    print_r(json_encode($data));
    exit;
}

//Search in User's
if ($_POST['action'] == 'searchUser') {
    $searchuser = $_POST['searchuser'];
    $searchtype = $_POST['type'];
    $list = $con->select("Users");
    $i = 1;
    $data = [];
    foreach ($list as $key => $value) {

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

        if (strpos(strtolower($value['name']), strtolower($searchuser)) !== false || strpos(strtolower($value['email']), strtolower($searchuser)) !== false || strpos(strtolower($country), strtolower($searchuser)) !== false  || strpos(strtolower($state), strtolower($searchuser)) !== false || strpos(strtolower($city), strtolower($searchuser)) !== false) {

            if ($searchtype == $value['user_type'] || $searchtype == 'null') {

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
                $value['city'] = $city;
                $value['state'] = $state;
                $value['country'] = $country;
                $value['Rentel_Id'] = $rentalproperty;
                $value['Owner_Id'] = $property;
                array_push($data, $value);
            }
        }
    }
    print_r(json_encode($data));
    exit;
}

//deleting Property
if ($_POST['action'] == 'delProperty') {
    $delid = $_POST['delid'];
    $objAws = new Aws($_AWS_S3_CREDENTIALS);
    $prop_pic = $con->select("properties", 'Photos', null, null, "property_id", $delid);
    $prop_pic = $prop_pic[0]['Photos'];
    $prop_pic = explode(",", $prop_pic);
    $foldername = 'property' . $delid;
    foreach ($prop_pic as $key => $value) {
        unlink("Files/propertyImg/$foldername/" . $value);
        $objAws->deletefile($_AWS_S3_CREDENTIALS, "propertyImg/" . $foldername . "/" . $value);
    }
    rmdir("Files/propertyImg/$foldername");
    $objAws->deletefile($_AWS_S3_CREDENTIALS, "propertyImg/" . $foldername);
    $con->delete("properties", "property_id", $delid);
    exit;
}

//Delete Pg
if ($_POST['action'] == 'delpg') {
    $delid = $_POST['delid'];
    $prop_pic = $con->select("pgtable", 'Photos', null, null, "pg_id", $delid);
    $foldername = 'pg' . $delid;
    $prop_pic = explode(",", $prop_pic[0]['Photos']);
    $objAws = new Aws($_AWS_S3_CREDENTIALS);
    foreach ($prop_pic as $key => $value) {
        unlink("Files/Pgimages/$foldername/" . $value);
        $objAws->deletefile($_AWS_S3_CREDENTIALS, "Pgimages/" . $foldername . "/" . $value);
        $objAws->deletefile($_AWS_S3_CREDENTIALS, "Pgimages/" . $foldername);
    }
    rmdir("Files/Pgimages/$foldername");
    $con->delete("pgtable", "pg_id", $delid);
    exit;
}

//Delete Users
if ($_POST['action'] == 'deluser') {
    $delid = $_POST['delid'];

    $objAws = new Aws($_AWS_S3_CREDENTIALS);
    $prop_pic = $con->select("Users", 'profile_pic', null, null, "user_id", $delid);
    $foldername = 'user' . $delid;
    unlink("Files/images/$foldername/" . $prop_pic[0]['profile_pic']);
    $objAws->deletefile($_AWS_S3_CREDENTIALS, "images/" . $foldername . "/" . $prop_pic[0]['profile_pic']);
    $objAws->deletefile($_AWS_S3_CREDENTIALS, "images/" . $foldername);
    rmdir("Files/images/$foldername");
    $con->delete("Users", "user_id", $delid);
    exit;
}
