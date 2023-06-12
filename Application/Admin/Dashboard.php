<?php
session_start();
require_once('lib/siteConstant.php');
if (!isset($_SESSION['adminUsername']) && !isset($_SESSION['adminPassword'])) {
    header("Location: index.php");
    exit;
}
$title = 'Admin Dashboard';
require_once('lib/Operations.php');

try {
    $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
    //   $con = new Operations('localhost','root','','property_dbms');
} catch (Exception $e) {
    echo "Error in connection of Db";
}

$count = $con->select('Users');
$usercount = count($count);
$count = $con->select('properties');
$propertycount = count($count);
if ($_POST['action'] == 'checklogin') {
    if (isset($_SESSION['adminUsername']) && isset($_SESSION['adminPassword'])) {
        echo "success";
    } else {
        echo 'failed';
    }
    exit;
}
require_once './AjaxCalls.php';
require_once('lib/header.php');
require_once('lib/navbar.php');
require_once('lib/sidebar.php');
?>
<div class="col-sm-9 ms-3 ">
    <div class="row maindiv gap-5 d-flex justify-content-center mt-3 overflow-auto " style="height: 86vh; ">
        <div class="row justify-content-center ">
            <div class="row mt-3 mb-2 ">
                <div class='row fs-1 mb-4 text-success'>
                    Welcome Admin :)
                </div>
                <div class="col-sm">
                    <div class=" d-flex border border-2 rounded">
                        <div class="col-3 p-3">
                            <img src="https://www.svgrepo.com/show/375862/plane.svg" alt="onboard employees" width="50px" height="80px">
                        </div>
                        <div class=" mt-4 ">
                            <small class="text-muted ">Total Users</small>
                            <p class="font-weight-bold"><?php echo $usercount ?></p>
                        </div>
                    </div>

                </div>
                <div class="col-sm">
                    <div class=" d-flex border border-2 rounded">
                        <div class=" p-3">
                            <img src="https://www.svgrepo.com/show/375779/clipboard.svg" alt="onboard employees" width="50px" height="80px">
                        </div>
                        <div class=" mt-4 ">
                            <small class="text-muted ">Total Properties </small>
                            <p class="font-weight-bold text-danger"><?php echo $propertycount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class=" d-flex border border-2 rounded">
                        <div class="col-3 p-3">
                            <img src="https://www.svgrepo.com/show/375768/calendar.svg" alt="onboard employees" width="50px" height="80px">
                        </div>
                        <div class="col-6 mt-4 ">
                            <small class="text-muted ">Current date</small>
                            <p class="font-weight-bold"><?php echo date("d/M/Y") . '<br>'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-sm-8 ">
                <div class='col-12 d-flex justify-content-center'>

                    <div id="carouselExampleCaptions" class="carousel slide ms-2 mt-2 rounded  " data-bs-ride="false">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner" data-bs-interval="2000">
                            <div class="carousel-item active">
                                <img src="https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=1600" class="d-block w-100 img-fluid" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>First slide label</h5>
                                    <p>Some representative placeholder content for the first slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <img src="https://images.pexels.com/photos/4846097/pexels-photo-4846097.jpeg?auto=compress&cs=tinysrgb&w=1600" class="d-block w-100 img-fluid" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Second slide label</h5>
                                    <p>Some representative placeholder content for the second slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <img src="https://images.pexels.com/photos/8293778/pexels-photo-8293778.jpeg?auto=compress&cs=tinysrgb&w=1600" class="d-block w-100 img-fluid" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Some representative placeholder content for the third slide.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 mt-2">
                <img src="https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="d-block w-100 img-fluid" alt="...">
            </div>

        </div>
        <hr>
    </div>

    <div class="modal fade" id="loginmodal" tabindex="-1" aria-labelledby="loginmodallabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-success " id="loginmodallabel">Login In</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id='modalbody'>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Username : </label>
                            <input type="text" class="form-control" name="username" id="username" aria-describedby="usernameErr" value="<?php if (isset($_COOKIE['adminusername'])) {
                                                                                                                                            echo $_COOKIE['adminusername'];
                                                                                                                                        } ?>" placeholder="">
                            <small id="usernameErr" class="form-text text-muted"></small>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Password : </label>
                            <input type="password" class="form-control" name="email" id="password" aria-describedby="passwordErr" value="<?php if (isset($_COOKIE['adminpassword'])) {
                                                                                                                                                echo $_COOKIE['adminpassword'];
                                                                                                                                            } ?>" placeholder="">
                            <small id="passwordErr" class="form-text text-muted"></small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rememberme">
                            <label class="form-check-label" for="rememberme">
                                Remember me
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="loginbtn">Login In</button>
                </div>
                <div id="showerror">
                </div>
            </div>
        </div>
    </div>
</div>
<script src='adminscript.js'></script>
<?php
require_once('lib/footer.php');
?>