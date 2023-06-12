<?php
session_start();
require_once('lib/siteConstant.php');
require_once('lib/Operations.php');
if (!isset($_SESSION['adminUsername']) && !isset($_SESSION['adminPassword'])) {
    header("Location: index.php");
    exit;
}
$title = 'Add User';
try {
    $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
    // $con = new Operations('localhost','root','','employees_dbms');
} catch (Exception $e) {
    echo "Error in connection to Db";
}


if (isset($_GET['id'])) {
    $userid = $_GET['id'];
    $userdata = $con->select('Users', '*', null, null, 'user_id', $userid);
    $userdata = $userdata[0];
    $title = 'Update User : ';
    $storefilename = "user" . $userdata['user_id'];
    $filename = $userdata['profile_pic'];
    // var_dump($userdata );
}

require_once '../../lib/siteConstant.php';
require_once '../../lib/site_variables.php';
require_once '../../vendor/autoload.php';
require_once '../../lib/Aws.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['Address'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $contact = $_POST['contact'];
    $type = $_POST['type'];
    $gender = $_POST['gender'];
    $filename = $_FILES['profile']['name'];
    $tmpfile = $_FILES['profile']['tmp_name'];
    if ($filename == "") {
        $filename = "";
        if (isset($_GET['id'])) {
            $filename = $userdata['profile_pic'];
        }
    }

    $values = array($name, $email, $gender, $password, $address, $country, $state, $city, $postcode, $contact, $type, $filename);
    $cols = array('name', 'email', 'gender', 'password', 'current_address', 'country', 'state', 'city', 'postcode', 'contact_no', 'user_type', 'profile_pic');



    $objAws = new Aws($_AWS_S3_CREDENTIALS);
    if (isset($_GET['id'])) {
        $data = $con->select('Users', "*", null, null, 'email', $email);
        if ($userdata['email'] == $email || empty($data)) {
            if (file_exists("Files/images/$storefilename/")) {
                if ($_FILES['profile']['name'] != null) {
                    if (!empty($userdata['profile_pic'])) {
                        unlink("Files/images/$storefilename/" . $userdata['profile_pic']);
                    }
                    // rmdir("Files/images/$storefilename/");
                    $objAws->deletefile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $userdata['profile_pic']);
                    move_uploaded_file($tmpfile, "Files/images/$storefilename/" . $filename);
                    $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $filename);
                }
            } else {
                mkdir("Files/images/$storefilename/", 0777, true);
                move_uploaded_file($tmpfile, "Files/images/$storefilename/" . $filename);
                $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $filename);
            }
            $con->update("Users", $cols, $values, 'user_id', $userid);
            $_SESSION['userupdated'] = "done";
            header("Location: Users.php");
        } else {
            $error = "Email already exist...";
        }
    } else {
        $data = $con->select('Users', "*", null, null, 'email', $email);
        if (!empty($data)) {
            $error = "Email already exist...";
        } else {
            $id = $con->insert('Users', $cols, $values);
            $storefilename = "user" . $id;
            if (!file_exists("Files/images/$storefilename/")) {
                if ($_FILES['profile']['name'] != null) {
                    mkdir("Files/images/$storefilename/", 0777, true);
                    move_uploaded_file($tmpfile, "Files/images/$storefilename/" . $filename);
                    $objAws->uploadFile($_AWS_S3_CREDENTIALS, "images/" . $storefilename . "/" . $filename);
                }
            }
            $_SESSION['useradded'] = "done";
            header("Location: Users.php");
        }
    }
}


require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>

<div class="col-sm-9 ms-3 ">
    <div class="row maindiv gap-5 d-flex justify-content-center mt-3 overflow-auto " style="height: 88vh; ">
        <div class="row d-flex ms-5 mt-5 justify-content-start ">
            <div class="col-1 d-flex justify-content-start">
                <a href="<?php echo SITE_URL; ?>PHPOPS/Application/Admin/Users.php" type="button" class="btn btn-outline-primary border border-3 border-primary ">
                    < Back</a>
            </div>
        </div>
        <div class='col-8 mt-5'>
            <form class="row g-3 border border-3 rounded p-3" method='post' enctype="multipart/form-data">
                <div class='col-12 d-flex justify-content-center'>
                    <div class="col-3 mx-auto">
                        <div class=' border border-2 border-dark p-2 justify-content-center rounded'>
                            <img id='profilesrc' class="w-100 rounded" src="<?php if (isset($_GET['id'])) {
                                                                                echo "https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/$storefilename/" . $filename;
                                                                            } else {
                                                                                echo "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
                                                                            } ?>" name='profiles' alt="logo">
                        </div>
                        <div class="mb-3 mt-3">
                            <input type="file" class="form-control" name="profile" id="profile" placeholder="" aria-describedby="fileHelpId">
                            <div id="fileHelpId" class="form-text"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value='<?php if (isset($_GET['id'])) {
                                                                                                echo $userdata['name'];
                                                                                            } else {
                                                                                                echo $_POST['name'];
                                                                                            } ?>' aria-describedby="nameErr">
                    <small id="nameErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4 ">
                    <label class="form-check-label" for="gender">Gender : </label>
                    <div class="form-check ps-5">
                        <input class="form-check-input" type="radio" value='M' name="gender" id="male" <?php if (isset($_GET['id'])) {
                                                                                                            if ($userdata['gender'] == 'M') {
                                                                                                                echo 'checked';
                                                                                                            }
                                                                                                        } else {
                                                                                                            if ($_POST['gender'] == 'M') {
                                                                                                                echo 'checked';
                                                                                                            }
                                                                                                        } ?>>
                        <label class="form-check-label" for="male">
                            Male
                        </label>
                    </div>
                    <div class="form-check ps-5">
                        <input class="form-check-input" type="radio" value='F' name="gender" id="female" <?php if (isset($_GET['id'])) {
                                                                                                                if ($userdata['gender'] == 'F') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            } else {
                                                                                                                if ($_POST['gender'] == 'F') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            } ?>>
                        <label class="form-check-label" for="female">
                            Female
                        </label>
                    </div>
                    <small id="genderErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value='<?php if (isset($_GET['id'])) {
                                                                                                echo $userdata['email'];
                                                                                            } else {
                                                                                                echo $_POST['email'];
                                                                                            } ?>' aria-describedby="emailErr">
                    <small id="emailErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value='<?php if (isset($_GET['id'])) {
                                                                                                            echo $userdata['password'];
                                                                                                        } else {
                                                                                                            echo $_POST['password'];
                                                                                                        }  ?>' aria-describedby="passwordErr">
                    <small id="passwordErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4">
                    <label for="country" class="form-label">Country</label>
                    <select id="country" class="form-select" onchange='selectstate()' name='country' aria-describedby="countryErr">
                        <option value="null">Choose...</option>
                        <?php
                        $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
                        // $con = new Operations('localhost','root','','employees_dbms');
                        if ($con) {
                            $countrys = $con->select('country');
                            if (isset($_GET['id'])) {
                                $temp = $userdata['country'];
                            } else {
                                $temp = $_POST['country'];
                            }
                            foreach ($countrys as $values) {
                                if ($values['country_id'] == $temp) {
                                    echo "<option value='" . $values['country_id'] . "' selected >" . $values['country_name'] . "</option>";
                                } else {
                                    echo "<option value= '" . $values['country_id'] . "' >" . $values['country_name'] . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <small id="countryErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4">
                    <label for="state" class="form-label">State</label>
                    <select id="state" class="form-select" onchange='selectcity()' aria-describedby="stateErr" value='' id='state' name='state'>
                        <option value="null">Choose...</option>
                        <?php
                        if ($con) {
                            $states = $con->select(array('State', 'country'), array('state_name', 'state_id'), 'right join', array('country_id', 'country_id'), 'B.country_id', $userdata['country']);

                            foreach ($states as $key => $value) {
                                if ($value['state_id']  == $userdata['state']) {
                                    echo "<option value='" . $value['state_id'] . "' selected>" . $value['state_name'] . "</option>";
                                } else {
                                    echo "<option value='" . $value['state_id'] . "'>" . $value['state_name'] . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <small id="stateErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-3">
                    <label for="city" class="form-label">City</label>
                    <select id="city" class="form-select" value='' id='city' name='city' aria-describedby="cityErr">
                        <option value="null">Choose...</option>
                        <?php
                        if ($con) {
                            $citys = $con->select(array('city', 'State'), array('city_name', 'city_id'), 'right join', array('state_id', 'state_id'), 'B.state_id', $userdata['state']);
                            foreach ($citys as $key => $value) {
                                if ($value['city_id']  == $userdata['city']) {
                                    echo "<option value='" . $value['city_id'] . "' selected>" . $value['city_name'] . "</option>";
                                } else {
                                    echo "<option value='" . $value['city_id'] . "'>" . $value['city_name'] . "</option>";
                                }
                            }
                        }
                        ?>


                    </select>
                    <small id="cityErr" class="form-text text-muted"></small>
                </div>
                <div class="col-7">
                    <label for="Address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="Address" name="Address" placeholder="1234 Main St" value='<?php if (isset($_GET['id'])) {
                                                                                                                                echo $userdata['current_address'];
                                                                                                                            } else {
                                                                                                                                echo $_POST['Address'];
                                                                                                                            } ?>' aria-describedby="addressErr">
                    <small id="addressErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-2">
                    <label for="Zip" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="Zip" maxlength="6" name='postcode' value='<?php if (isset($_GET['id'])) {
                                                                                                echo $userdata['postcode'];
                                                                                            } else {
                                                                                                echo $_POST['postcode'];
                                                                                            } ?>' aria-describedby="postcodeErr">
                    <small id="postcodeErr" class="form-text text-muted"></small>
                </div>
                <div class="col-8">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" class="form-select" name='type' aria-describedby="typeErr">
                        <?php if (isset($_GET['id'])) {
                            $temp =  $userdata['user_type'];
                        } else {
                            $temp = $_POST['type'];
                        } ?>
                        <option value='null' <?php if ($temp == null) {
                                                    echo "selected";
                                                } ?>>Choose...</option>
                        <option value='rentel' <?php if ($temp == 'rentel') {
                                                    echo "selected";
                                                } ?>>Rental</option>
                        <option value='owner' <?php if ($temp == 'owner') {
                                                    echo "selected";
                                                } ?>>Owner</option>
                    </select>
                    <small id="typeErr" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4">
                    <label for="contact" class="form-label">Contact No.</label>
                    <input type="text" class="form-control" id="contact" maxlength="11" name='contact' value='<?php if (isset($_GET['id'])) {
                                                                                                                    echo $userdata['contact_no'];
                                                                                                                } else {
                                                                                                                    echo $_POST['contact'];
                                                                                                                } ?>' aria-describedby="contactErr">
                    <small id="contactErr" class="form-text text-muted"></small>
                </div>
                <div class="col-12">
                    <button type="submit" name='submit' class=" btn btn-primary" id='addupdateUsers'><?php if (isset($_GET['id'])) {
                                                                                                            echo "Update";
                                                                                                        } else {
                                                                                                            echo "Submit";
                                                                                                        } ?></button>
                </div>
            </form>
            <div class="mt-3 fs-5 text-danger">
                <?php echo $error; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('change', "#profile", (e) => {
            var src = URL.createObjectURL(e.target.files[0])
            $("#profilesrc").attr("src", src);
        });
    });
</script>
<script src='adminscript.js'></script>
<?php
require_once('lib/footer.php');
?>