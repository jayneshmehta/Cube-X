<nav class="navbar navbar-light justify-content-center bg-light border-bottom  border-3 border-success sticky-top">
  <div class="container-fluid d-inline-block">
    <ul class="nav ">
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo SITE_URL; ?>index.php" aria-current="page">
          <img src="<?php echo SITE_URL; ?>/Assets/images/finalApplogo.png" alt="sitelogo" width="200" height="50" class="d-inline-block align-text-top img-fluid ">
        </a>
      </li>
      <li class=' d-flex gap-3 ' style="margin: 25px;">
        <a class="nav-link text-success text-center fs-5 " href="<?php echo SITE_URL; ?>PHPOPS/Application/" aria-current="page"><strong>Home</strong></a>
        <a class="nav-link text-success text-center fs-5 " href="<?php echo SITE_URL; ?>PHPOPS/Application/showproperties.php" aria-current="page"><strong>Properties</strong></a>
        <a class="nav-link text-success text-center fs-5 " href="<?php echo SITE_URL; ?>PHPOPS/Application/showpgs.php" aria-current="page"><strong>Pg/co-living</strong></a>
        <a class="nav-link text-success text-center fs-5 " href="#" aria-current="page"><strong>Contact Us</strong></a>
      </li>
      <li>

      </li>
      <li class="nav-item ms-auto d-flex gap-2 " style="margin: 30px;">

        <button class="btn btn-outline-warning  mx-auto nav-link active rounded-pill border border-success <?php if(isset($_SESSION['useremail']) && isset($_SESSION['userpassword'])){echo 'd-none';}?> " id="login" data-bs-toggle="modal" data-bs-target="#userlogin" aria-current="page"><strong>Log In</strong></button>
        <button class="btn btn-outline-danger  mx-auto nav-link active rounded-pill  border border-success <?php if(!isset($_SESSION['useremail']) && !isset($_SESSION['userpassword'])){echo 'd-none';}?>" id="logout" aria-current="page">Log out</button>
        <a href="<?php echo SITE_URL ?>PHPOPS/Application/Registeruser.php" class="btn btn-outline-primary rounded-pill  mx-auto nav-link active  border  border-success <?php if(isset($_SESSION['useremail']) && isset($_SESSION['userpassword'])){echo 'd-none';}?>" id="signin"><strong>Sign Up</strong></a>
        <div>
          <?php
          $photo = $con->select("Users", "profile_pic", null, null, "email", $_SESSION['useremail']);
          $photo = $photo[0]['profile_pic'];
          ?>
          <a href="<?php echo SITE_URL ?>PHPOPS/Application/userinfo.php"> <img class='rounded-circle border border-2 border-success <?php if (!isset($_SESSION['useremail'])) {
                                                                                                                                    echo 'd-none';
                                                                                                                              } ?>' id='profile' width='50px' height='50px' src="<?php if($photo != "nopic") {echo "https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user". $_COOKIE['user_id'] . "/" . $photo;}else{echo "https://cdn.pixabay.com/photo/2015/03/04/22/35/avatar-659651_640.png";} ?>" alt="profile"></a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<div class="modal fade show" id="userlogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-success" id="userloginmodaltitle">Login : </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='loginmodal'>
        <form action="" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Email : </label>
            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailErr" value='<?php echo $_COOKIE['useremail']; ?>' placeholder="">
            <small id="emailErr" class="form-text text-muted"></small>
          </div>
          <div class="mb-3">
            <label for="" class="form-label">Password : </label>
            <input type="password" class="form-control" name="password" id="password" aria-describedby="passwordErr" value='<?php echo $_COOKIE['userpassword']; ?>' placeholder="">
            <small id="passwordErr" class="form-text text-muted"></small>
          </div>
          <div class="form-check d-flex justify-content-between">
            <div>
              <input class="form-check-input" type="checkbox" value="" id="rememberme">
              <label class="form-check-label" for="rememberme">
                Remember me
              </label>
            </div>
            <div>
              <a class="text-primary me-4" data-bs-target="#forgetpasswordmodal" data-bs-toggle="modal" data-bs-dismiss="modal" role="button" id='forgetpassword'> Forget passsword ? </a>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success " id="userloginbtn">Login </button>
      </div>
      <div id='showError'>
      </div>
    </div>
  </div>
</div>

<div class="modal fade rounded" id="forgetpasswordmodal" aria-hidden="true" aria-labelledby="forgetpasswordmodal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success" id="exampleModalToggleLabel2">Forget password : </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <label for="email" class="form-label">Email : </label>
          <div class="mb-3 row d-flex">
            <div class='col-8'>
              <input type="text" class="form-control" name="email" id="otpemail" aria-describedby="emailErr" value='' placeholder="Enter Email ..">
              <small id="otpemailErr" class="form-text text-muted"></small>
            </div>
            <div class='col-4'>
              <button type='button' name="submit" id="sendotponlogin" class="btn btn-outline-danger  p-1">Send OTP</button>
            </div>
          </div>
          <hr>
          <div id='enterotp'>
            <div class="mb-3">
              <label for="" class="form-label">OTP : </label>
              <input type="text" class="form-control" name="FPotp" id="FPotp" aria-describedby="otpErr" placeholder="Enter OTP">
              <small id="otpErr" class="form-text text-muted"></small>
            </div>
          </div>

        </form>
        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-target="#userlogin" data-bs-toggle="modal" data-bs-dismiss="modal"> Back </button>
          <button class="btn btn-success" type="submit" id='loginthroughFP'>Login</button>
        </div>
        <div id='showOtpMsg'>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="contactdetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success " id="exampleModalLabel">Owner Details : </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='ownerdetails'>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="resetpassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success " id="exampleModalLabel">Reset password : </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='resetpassword'>
        <form action="" method="post">
          <div class="mb-3">
            <label for="newPassword" class="form-label">New password : </label>
            <input type="text" class="form-control" name="newPassword" id="newPassword" aria-describedby="passErr" placeholder="Enter password">
            <small id="passErr" class="form-text text-muted"></small>
          </div>
          <div class="mb-3">
            <label for="conPassword" class="form-label">Confirm Password : </label>
            <input type="text" class="form-control" name="conPassword" id="conPassword" aria-describedby="conPasswordErr" placeholder="confirm password">
            <small id="conPasswordErr" class="form-text text-muted"></small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <!-- <button class="btn btn-success" type="submit" id='loginthroughFP' >Login</button> -->
        <button type="submit" class="btn btn-success" id='changePassword'>Change</button>
      </div>
      <div id='wrongPassword'>
      </div>
    </div>
  </div>
</div>