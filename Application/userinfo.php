<?php
session_start();
if (!isset($_SESSION['useremail']) && !isset($_SESSION['userpassword'])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION['updateeduser'] == 'done') {
    echo '<script>alert("Whoo...!.. User profile updated !!")</script>';
    unset($_SESSION['updateeduser']);
}
if ($_SESSION['userpropertyAdded'] == "done") {
    echo '<script>alert("Whoo...!.. User Property is added !!")</script>';
    unset($_SESSION['userpropertyAdded']);
}

if ($_SESSION['userpropertyupdated'] == "done") {
    echo '<script>alert("Whoo...!.. User Property is Updated !!")</script>';
    unset($_SESSION['userpropertyupdated']);
}
$title = "User Info";
require_once '../lib/siteConstant.php';
require_once '../lib/site_variables.php';
require_once '../vendor/autoload.php';
require_once '../lib/Aws.php';

require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';
try{
    $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
    // $con = new Operations('localhost','root','','property_dbms');
}catch(Exception $e){
    echo "Error in Connect to Db";
}

//AJAX CALLS
require_once './Ajaxcalls.php';

$user_id = $_COOKIE['user_id'];
$userdata = $con->select('Users', "*", null, null, 'user_id', $user_id);
$userdata = $userdata[0];

require_once 'userlib/header.php';
require_once 'userlib/navbar.php';
?>
<div class='row gx-0 d-flex justify-content-center'>
    <div class='col-6 d-flex justify-content-center mt-3'>
        <img class=' border border-3 border-success rounded p-2 ' width='300px' height='300px' data_id = "<?php echo $userdata['profile_pic']; ?>"  src="<?php if($userdata['profile_pic'] != "nopic" ){echo " https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user".$userdata['user_id'] . "/" . $userdata['profile_pic']; }else{echo "https://cdn.pixabay.com/photo/2015/03/04/22/35/avatar-659651_640.png";}; ?>" alt="">
    </div>
</div>
<div class='row gx-0 mt-4 d-flex justify-content-center'>
    <div class="col-5 d-flex justify-content-center gap-5">
        <div class='col-7 fs-4  '>
            <p><strong>Name : </strong><?php echo $userdata['name']; ?></p>
            <p><strong>Email : </strong><?php echo $userdata['email']; ?></p>
        </div>
        <div class='col-5 fs-4 text-left'>
            <p><strong>Contact : </strong><?php echo $userdata['contact_no']; ?></p>
            <p><strong> Gender : </strong><?php if ($userdata['gender'] == 'M') {
                                                echo 'Male';
                                            } else {
                                                echo "Female";
                                            } ?></p>
        </div>
    </div>
</div>
<div class='row gx-0 fs-4 text-center'>
    <?php $country = $con->select('country', 'country_name', null, null, 'country_id', $userdata['country']);
    $country = $country[0]['country_name'];
    $state = $con->select('State', 'state_name', null, null, 'state_id', $userdata['state']);
    $state = $state[0]['state_name'];
    $city = $con->select('city', 'city_name', null, null, 'city_id', $userdata['city']);
    $city = $city[0]['city_name'];
    $add = $userdata['current_address'] . "," . $city . "," . $state . "," . $country;
    ?>
    <strong>Address : </strong><span><?php echo $add; ?></span>
</div>
<div class='row gx-0 fs-4 mt-4 text-center'>
    <div class='col '>
        <a href="<?php echo SITE_URL ?>PHPOPS/Application/Registeruser.php?id=<?php echo base64_encode($userdata['user_id']); ?>" class='btn btn-warning me-3' id='editUser'>Edit Details</a>
        <a href="<?php echo SITE_URL ?>PHPOPS/Application/Addproperty.php?id=<?php echo base64_encode($userdata['user_id']); ?>" class='btn btn-warning me-3' id='AddProperty'>Add Property</a>
        <a href="<?php echo SITE_URL ?>PHPOPS/Application/Addpg.php?id=<?php echo base64_encode($userdata['user_id']); ?>" class='btn btn-warning' id='AddPg'>Add PG/Co-living</a>
    </div>
</div>
</div>
</div>
<div class='row gx-0 fs-4 mt-5 ms-5'>
    <?php

    $propdata = $con->select(["properties", 'Users'], "*", 'left join', ['Owner_id', 'user_id'], 'user_id', $userdata['user_id']);
    if (!empty($propdata)) {
        echo "<strong class='text-primary'>Your Added Property's  : </strong><br><br>";
        foreach ($propdata as $key => $value) {
            $propcountry = $con->select('country', 'country_name', null, null, 'country_id', $value['country']);
            $propcountry = $propcountry[0]['country_name'];
            $propstate = $con->select('State', 'state_name', null, null, 'state_id', $value['state']);
            $propstate = $propstate[0]['state_name'];
            $propcity = $con->select('city', 'city_name', null, null, 'city_id', $value['city']);
            $propcity = $propcity[0]['city_name'];
            $propadd = $value['current_address'] . "," . $propcity . "," . $propstate . "," . $propcountry; ?>
            <hr>
            <div class='row  gx-0'>
                <div class='col-7'>
                    <p><strong>Property Type : </strong>Owned </p>
                    <p><strong>Property Name : </strong><?php echo $value['property_name'] ?></p>
                    <p><strong>short description : </strong><?php echo $value['sort_descreption'] ?></p>
                    <p><strong>Long description : </strong><?php echo $value['long_discription'] ?></p>
                    <p><strong>Area : </strong><?php echo $value['Area'] ?>sqft</p>
                    <p><strong>Rooms : </strong><?php echo $value['Rooms'] ?></p>
                    <p><strong>Price : </strong><?php echo $value['price'] ?></p>
                    <p><strong>Address : </strong><?php echo $propadd ?></p>
                    <p><strong>Status : </strong><?php echo $value['status'] ?></p>

                </div>
                <div class='col-4 d-flex mt-5 mb-5 justify-content-center'>
                    <img class='img-fluid border border-3 border-dark p-2' width='400px' height='400px' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property<?php echo $value['property_id'] . "/";
                                                                                                                                                                                                $photos = explode(",", $value['Photos']);
                                                                                                                                                                                                echo $photos[0]; ?>'>
                </div>
            </div>
            <div class='row  gx-0 d-flex mb-5 justify-content-center'>
                <div class='col-12 gap-5 d-flex mb-5 justify-content-center'>
                    <a id='upd<?php echo $value['property_id'] ?>' data-id='<?php echo $userdata['user_id'] ?>' class='upd btn btn-warning'>Edit Property</a>
                    <a id='del<?php echo $value['property_id'] ?>' class='del btn btn-danger'>Delete Property</a>
                </div>
            </div>
            <hr>
    <?php
        }
    }
    ?>

</div>
<div class='row gx-0 fs-4 mt-5 ms-5'>
    <?php
    $pgdata = $con->select(["pgtable", 'Users'], "*", 'left join', ['Owner_id', 'user_id'], 'user_id', $userdata['user_id']);
    if (!empty($pgdata)) {
        echo "<strong class='text-primary'>Your Added PG's : </strong><br><br>";
        foreach ($pgdata as $key => $value) {
            $pgcountry = $con->select('country', 'country_name', null, null, 'country_id', $value['country']);
            $pgcountry = $pgcountry[0]['country_name'];
            $pgstate = $con->select('State', 'state_name', null, null, 'state_id', $value['state']);
            $pgstate = $pgstate[0]['state_name'];
            $pgcity = $con->select('city', 'city_name', null, null, 'city_id', $value['city']);
            $pgcity = $pgcity[0]['city_name'];
            $pgadd = $value['current_address'] . "," . $pgcity . "," . $pgstate . "," . $pgcountry; ?>
            <hr>
            <div class='row  gx-0'>
                <div class='col-7'>
                    <p><strong>PG Name : </strong><?php echo $value['pg_name'] ?></p>
                    <p><strong>short description : </strong><?php echo $value['sort_descreption'] ?></p>
                    <p><strong>Long description : </strong><?php echo $value['long_discription'] ?></p>
                    <p><strong>Total Rooms : </strong><?php echo $value['Rooms'] ?></p>
                    <p><strong>Type of Rooms : </strong><?php echo $value['Room_capacity']; ?></p>
                    <p><strong>Facilities : </strong><?php echo $value['Facilities']; ?></p>
                    <p><strong>Price : </strong><?php echo $value['price'] ?>/Month</p>
                    <p><strong>Address : </strong><?php echo $pgadd ?></p>
                    <p><strong>Status : </strong><?php echo $value['status'] ?></p>

                </div>
                <div class='col-4 d-flex mt-5 mb-5 justify-content-center'>
                    <img class=' border border-3 border-dark p-2 align-item-center ' width='500px' height='500px' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg<?php echo $value['pg_id'] . "/";
                                                                                                                                                                                                $photos = explode(",", $value['Photos']);
                                                                                                                                                                                                echo $photos[0]; ?>'>
                </div>
            </div>
            <div class='row  gx-0 d-flex mb-5 justify-content-center'>
                <div class='col-12 gap-5 d-flex mb-5 justify-content-center'>
                    <a id='updpg<?php echo $value['pg_id'] ?>' data-id='<?php echo $userdata['user_id'] ?>' class='updpg btn btn-warning'>Edit PG</a>
                    <a id='delpg<?php echo $value['pg_id'] ?>' class='delpg btn btn-danger'>Delete PG</a>
                </div>
            </div>
            <hr>
    <?php
        }
    }
    if (empty($pgdata) && empty($propdata)) {
        echo "<div class='text-danger fs-5 text-center '>You have not added any property ..</div>";
    }
    ?>

    <script src="userscript.js"></script>
    <script>
        
        //to show page of Property Update page.. 
        $(document).on('click', '.upd', function() {
            var updid = (this.id).slice(3);
            var userid = $(this).data("id");
            window.location.href = "<?php echo SITE_URL ?>/PHPOPS/Application/Addproperty.php?propid=" + btoa(updid) + "++" + btoa(userid);
        });

        //to show page of pg Update page.. 
        $(document).on('click', '.updpg', function() {
            var updid = (this.id).slice(5);
            var userid = $(this).data("id");
            console.log(btoa(updid) + "++" + btoa(userid));
            window.location.href = "<?php echo SITE_URL ?>/PHPOPS/Application/Addpg.php?pgid=" + btoa(updid) + "++" + btoa(userid);
        });
    </script>
    <?php
    require_once 'userlib/footer.php';
    ?>