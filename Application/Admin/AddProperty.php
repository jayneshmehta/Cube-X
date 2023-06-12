<?php
session_start();
require_once 'lib/siteConstant.php';
require_once 'lib/Operations.php';
if (!isset($_SESSION['adminUsername']) && !isset($_SESSION['adminPassword'])) {
    header("Location: index.php");
    exit;
}
$title = 'Add Property' ;
require_once '../../lib/siteConstant.php';
require_once '../../lib/site_variables.php';
require_once '../../vendor/autoload.php';
require_once '../../lib/Aws.php';

$propId = $_GET['id'];
try {
    $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
    // $con = new Operations('localhost','root','','employees_dbms');
} catch (Exception $e) {
    echo "Error in connection to Db";
}
if ($con) {
    $result = $con->select('properties', "*", null, null, "property_id", $propId);
    $result = $result[0];


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
        $OwnerId = $_POST['OwnerId'];
        $RentalId = $_POST['RentalId'];
        $rating = $_POST['rating'];
        $propertyphoto = $_FILES['propertipic']['name'];
        $proptempname = $_FILES['propertipic']['tmp_name'];

        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $usercountry = $_POST['usercountry'];
        $usercity = $_POST['usercity'];
        $userstate = $_POST['userstate'];
        $userstr_address = $_POST['userstr_address'];
        $gender = $_POST['gender'];
        $contactNo = $_POST['contact'];
        $profilephoto = $_FILES['profile_pic']['name'];
        $profiletempname = $_FILES['profile_pic']['tmp_name'];

        $propertyphoto = implode(',', $propertyphoto);
        $proptempname = implode(',', $proptempname);

        $photoflag = true;

        if($RentalId == 'norental'){
            $RentalId = '';
        }
 

        $imgtype = array("image/jpeg", "image/png", "image/jpg");
        foreach ($_FILES['propertipic']['type'] as $key => $value) {
            if (!in_array($value, $imgtype) && !in_array($_FILES['profile_pic']['type'], $imgtype)) {
                $photoflag = false;
                break;
            }
        }
        if (!$photoflag) {
            $error = "Image should be in jpeg/png/jpg Formate only..";
        } else {
            $objAws = new Aws($_AWS_S3_CREDENTIALS);
            if ($name != null) {

                $usercols = array("user_type", "name", "email", "password", "gender", "country", "state", "city", "current_address", "profile_pic", "contact_no", "postcode");
                $uservalues = array("owner", $name, $email, $password, $gender, $usercountry, $userstate, $usercity, $userstr_address, $profilephoto, $contactNo, $Zip);
                $newuserid =  $con->insert("Users", $usercols, $uservalues);

                $storefilename = "user" . $newuserid;
                if (file_exists("Files/images/$storefilename/")) {
                    if ($_FILES['profile_pic']['name'] != null) {
                        unlink("Files/images/$storefilename/" . $newuserid[0]['profile_pic']);
                    }
                    try {
                        move_uploaded_file($profiletempname, "Files/images/$storefilename/" . $profilephoto);
                        $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $profilephoto);
                    } catch (Exception $e) {
                        echo "Failed to upload image";
                    }
                } else {
                    mkdir("Files/images/$storefilename/", 0777, true);
                    try {
                        move_uploaded_file($profiletempname, "Files/images/$storefilename/" . $profilephoto);
                        $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $profilephoto);
                    } catch (Exception $e) {
                        echo "Failed to upload image";
                    }
                }
                $OwnerId = $newuserid;
            }
            $cols = array("property_name", "sort_descreption", "long_discription", "Rooms", "Area", "price", "catagories", "country", "state", "city", "local_address", "postcode", "type", "status", "Photos", "Owner_Id", "Rental_Id", "Rating");
            $values = array("$PropName", "$shortdesc", "$longdesc", "$room", "$area", "$price", "$catagory", "$country", "$state", "$city", "$str_address", "$Zip", "$price", "$status", $propertyphoto, "$OwnerId", "$RentalId", "$rating");

            // var_dump($values);
            $newpropid = $con->insert("properties", $cols, $values);

            $dbphotos = $con->select("properties", "Photos", null, null, "property_id", $newpropid);
            $dbphotos = $dbphotos[0]['Photos'];
            $dbphotos =  explode(",", $dbphotos);

            $propertyphoto = explode(",", $propertyphoto);
            $proptempname = explode(",", $proptempname);


            $storefilename = "property" . $newpropid;
            if ($_FILES['profile_pic']['tmp_name'][0] == '') {
                mkdir("Files/propertyImg/$storefilename/", 0777, true);
                foreach ($propertyphoto as $key => $value) {
                    try {
                        move_uploaded_file($proptempname[$key], "Files/propertyImg/$storefilename/" . $value);
                        $objAws->uploadFile($_AWS_S3_CREDENTIALS, "propertyImg/" . $storefilename . "/" . $value);
                    } catch (Exception $e) {
                        echo "Failed to upload image";
                    }
                }
            }
            $_SESSION['propertyAdded'] = "done";
            header('location:properties.php');
        }
    }
}


require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>
<div class='col-10'>
    <div class="row ms-2 maindiv gx-0 overflow-auto" style="height: 88vh;">
        <div class="row d-flex ms-5 mt-5 justify-content-between ">
            <div class="col-1 d-flex justify-content-start">
                <a href="<?php echo SITE_URL; ?>PHPOPS/Application/Admin/properties.php" type="button" class="btn btn-outline-primary border border-3 border-primary ">
                    < Back</a>
            </div>
            <div class="col-2 d-flex justify-content-start">
                <a href="<?php echo SITE_URL; ?>PHPOPS/Application/Admin/Addpg.php" type="button" class="btn btn-outline-success border border-3 border-warning ">
                    Add PG/Co-living</a>
            </div>
        </div>
        <div class="col-10">
            <form class="row ms-3 mt-5 p-2 border border-3 border-primary" method="POST" enctype="multipart/form-data">
                <div class="row  d-flex-row justify-content-center ">
                    <div class='col-md-6'>
                        <img src="https://cdn4.iconfinder.com/data/icons/social-communication/142/add_photo-512.png" alt="home image" id='profileimg' class="w-100 border border-2 p-2 img-fluid" srcset="">
                    </div>
                    <div class="col-md-8">
                        <label for="" class="form-label">Choose file</label>
                        <input type="file" class="form-control" name="propertipic[]" id="propertipic" placeholder="" aria-describedby="proppic" multiple>
                        <div id="proppic" class="form-text"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="propname" class="form-label">Property Name : </label>
                    <input type="text" class="form-control" name="propname" id="propname" aria-describedby="Errpropname" value='<?php echo $result['property_name'] ?>'>
                    <small id="Errpropname" class="form-text text-muted "></small>
                </div>
                <div class="col-md-6">
                    <label for="shortdesc" class="form-label">Short Description: </label>
                    <input type="text" class="form-control" id="shortdesc" name="shortdesc" aria-describedby="Errshortdesc" value='<?php echo $result['sort_descreption'] ?>'>
                    <small id="Errshortdesc" class="form-text text-muted "></small>
                </div>
                <label for="longdesc">Long Description : </label>
                <div class="form-floating mt-2">
                    <textarea class="form-control" placeholder="" id="longdesc" name="longdesc" aria-describedby="Errlongdesc" style="height: 100px"><?php echo trim($result['long_discription']); ?></textarea>
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
                    <select id="state" class="form-select" onchange='selectcity()' value='' id='state' aria-describedby="Errstate" name='state'>
                        <option value="empty" disabled>Choose...</option>
                        <?php
                        // $states = $con->select(array('State', 'country'), array('state_name', 'state_id'), 'right join', array('country_id', 'country_id'), 'B.country_id', $result['country']);
                        // foreach ($states as $key => $value) {
                        //     echo "<option value='" . $value['state_id'] . "'";
                        //     if ($value['state_id'] == $result['state']) {
                        //         echo 'selected';
                        //     }
                        //     echo ">" . $value['state_name'] . "</option>";
                        // }
                        ?>
                    </select>
                    <small id="Errstate" class="form-text text-muted "></small>
                    <button class='btn'></button>
                </div>
                <div class="col-md-4 mt-3">
                    <label for="city" class="form-label">city : </label>
                    <select id="city" class="form-select" value='' id='city' aria-describedby="Errcity" name='city'>
                        <option selected disabled>Choose...</option>
                        <?php
                        // $citys = $con->select(array('city', 'State'), array('city_name', 'city_id'), 'right join', array('state_id', 'state_id'), 'B.state_id', $result['state']);
                        // foreach ($citys as $key => $value) {
                        //     echo "<option value='" . $value['city_id'] . "'";
                        //     if ($value['city_id'] == $result['city']) {
                        //         echo 'selected';
                        //     }
                        //     echo ">" . $value['city_name'] . "</option>";
                        // }
                        ?>
                    </select>
                    <small id="Errcity" class="form-text text-muted "></small>

                </div>
                <div class="col-4 mt-3">
                    <label for="inputAddress" class="form-label">Address :</label>
                    <input type="text" class="form-control" id="inputAddress" name="str_address" aria-describedby="Erraddress" placeholder="1234 Main St" value="<?php echo $result['local_address']; ?>">
                    <small id="Erraddress" class="form-text text-muted "></small>

                </div>
                <div class="col-md-2 mt-3">
                    <label for="inputZip" class="form-label">Zip :</label>
                    <input type="text" class="form-control" id="inputZip" name="Zip" maxlength="6" aria-describedby="Errzip" value="<?php echo $result['postcode']; ?>">
                    <small id="Errzip" class="form-text text-muted "></small>

                </div>
                <div class="col-md-2 mt-3">
                    <label for="Area" class="form-label">Area :</label>
                    <input type="text" class="form-control" id="area" name="area" aria-describedby="Errarea" value="<?php echo $result['Area']; ?>">
                    <small id="Errarea" class="form-text text-muted "></small>

                </div>
                <div class="col-md-4 mt-3">
                    <label for="room" class="form-label">Room : </label>
                    <select id="room" class="form-select" value='' aria-describedby="Errrooms" name='room'>
                        <option selected>Choose...</option>
                        <?php
                        $rooms = $con->select("properties", "Rooms", null, null, "property_id", $result['property_id']);
                        $rooms = $rooms[0];
                        $i = 0;
                        while ($i <= 10) {
                            echo "<option value= '" . $i . "'>" . $i . "</option>";
                            $i++;
                        }
                        ?>
                    </select>
                    <small id="Errrooms" class="form-text text-muted "></small>

                </div>
                <div class="col-md-4 mt-3">
                    <label for="catagory" class="form-label">catagory : </label>
                    <select id="catagory" class="form-select" aria-describedby="Errcat" name='catagory'>
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
                    <select id="type" class="form-select" value='' aria-describedby="Errtype" name='type'>
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
                    <label for="price" class="form-label">Price :</label>
                    <input type="number" class="form-control" id="price" name="price" aria-describedby="Errprice" value="<?php echo $result['price']; ?>">
                    <small id="Errprice" class="form-text text-muted "></small>

                </div>
                <div class="col-md-6 mt-3">
                    <label for="OwnerId" class="form-label">Owner : </label>
                    <select id="OwnerId" class="form-select" onclick="openuserdiv()" aria-describedby="Errowner" name='OwnerId'>
                        <option value='AddUser'><b>Add New user</b></option>
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
                <div class="col-md-6 mt-3">
                    <label for="RentalId" class="form-label">Rental Id : </label>
                    <select id="RentalId" class="form-select" value='' aria-describedby="Errrent" name='RentalId'>
                        <option selected value = "norental" >No Rental...</option>
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
                <div>
                    <hr>
                </div>
                <div class="row" id="userdetail">
                    <div class="col-md-4 mt-2 ">
                        <label for="username" class="form-label">Name : </label>
                        <input type="text" class="form-control" name="username" id="username" aria-describedby="Errusername" value=''>
                        <small id="Errusername" class="form-text text-muted "></small>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="userEmail" class="form-label">Email : </label>
                        <input type="text" class="form-control" name="email" id="email" aria-describedby="Erremail" value=''>
                        <small id="Erremail" class="form-text text-muted "></small>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="userpassword" class="form-label">Password : </label>
                        <input type="password" class="form-control" name="password" id="password" minlength="6" aria-describedby="Errpassword" value=''>
                        <small id="Errpassword" class="form-text text-muted "></small>
                    </div>
                    <div class="col-md-4 mt-2">

                        <label for="" class="form-label">Gender: </label>
                        <div class=" form-check">
                            <input class="form-check-input" type="radio" name="gender" value='M' id="male">
                            <label class="form-check-label" for="male">
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value='F' id="female">
                            <label class="form-check-label" for="female">
                                Female
                            </label>
                        </div>
                        <small id="genderErr" class="form-text text-muted "></small>
                    </div>
                    <div class="col-md-4 mt-3 mt-2">
                        <label for="usercountry" class="form-label">Country</label>
                        <select id="usercountry" class="form-select" onchange='userselectstate()' aria-describedby="Errusercountry" name='usercountry'>
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
                        <small id="Errusercountry" class="form-text text-muted "></small>

                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="userstate" class="form-label">State</label>
                        <select id="userstate" class="form-select" onchange='userselectcity()' value='' aria-describedby="Erruserstate" id='userstate' name='userstate'>
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
                        <small id="Erruserstate" class="form-text text-muted "></small>

                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="usercity" class="form-label">city : </label>
                        <select id="usercity" class="form-select" aria-describedby="Errusercity" name='usercity'>
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
                        <small id="Errusercity" class="form-text text-muted "></small>

                    </div>

                    <div class="col-4 mt-3">
                        <label for="userAddress" class="form-label">Street address :</label>
                        <input type="text" class="form-control" id="userAddress" name="userstr_address" aria-describedby="Erruseraddress" placeholder="1234 Main St" value="<?php echo $result['userstr_address']; ?>">

                        <small id="Erruseraddress" class="form-text text-muted "></small>
                    </div>
                    <div class="col-4 mt-3">
                        <label for="profile_photo" class="form-label">Add profile photo</label>
                        <input type="file" class="form-control" name="profile_pic" id="profile_pic" placeholder="Errprofile" aria-describedby="fileHelpId">
                        <div id="Errprofile" class="form-text"></div>
                    </div>
                    <div class="col-4 mt-3">
                        <label for="" class="form-label">Contact no. </label>
                        <input type="number" class="form-control" name="contact" id="contact" aria-describedby="Errcontact" placeholder="">
                        <small id="Errcontact" class="form-text text-muted"></small>
                    </div>
                </div>

                <div class="col-md-12 gap-4 d-flex justify-content-center position-relative mt-5 pt-2">

                    <div class="col-md-4 mt-3">
                        <label for="type" class="form-label">Status : </label>
                        <select id="userstatus" class="form-select" value='' aria-describedby="Errstatus" name='status'>
                            <option selected disabled>Choose...</option>
                            <option value="SOLD" <?php if ($result['status'] == 'sold') {
                                                        echo 'selected';
                                                    } ?>>SOLD</option>
                            <option value="UNSOLD" <?php if ($result['status'] == 'unsold') {
                                                        echo 'selected';
                                                    } ?>>UNSOLD</option>
                        </select>
                        <small id="Errstatus" class="form-text text-muted"></small>

                    </div>
                </div>
                <div class="col-12 mt-3 d-flex gap-3 justify-content-center ">
                    <button type="submit" class="btn btn-danger" id="AddProp" name='AddProp'>Add property</button>
                    <!-- <button type="reset" class="btn btn-">Reset</button> -->
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(document).on('change', "#propertipic", (e) => {
                var src = URL.createObjectURL(e.target.files[0])
                $("#profileimg").attr("src", src);
            })
        })
    </script>
    <script src="adminscript.js"></script>
    <?php
    require_once('lib/footer.php');
    ?>