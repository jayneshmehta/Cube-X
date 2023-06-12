<?php
session_start();
require_once 'lib/siteConstant.php';
require_once 'lib/Operations.php';
if (isset($_SESSION['adminUsername']) && isset($_SESSION['adminPassword'])) {
  header("Location: Dashboard.php");
  exit;
}
$title = 'Admin Login'; 
try {
  $con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
  // $con = new Operations('localhost','root','','Property_DBMS');
} catch (Exception $e) {
  echo "Error in connection to Db";
}

if ($con) {
  if ($_POST['action'] == "adminlogin") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = $_POST['rememberme'];
    $dbresult = $con->select('admin');
    $check = ($dbresult[0]);
    $flag = 'notok';
    if ($check['Username'] == $username && $check['Password'] == $password) {
      echo "ok";
      $flag = 'ok';
      session_start();
      $_SESSION['adminUsername'] = $username;
      $_SESSION['adminPassword'] = $password;
    } else {
      echo "login failed";
    }
    if ($flag == 'ok' && $rememberMe == "on") {
      setcookie('adminusername', $username, time() + (3600 * 24), "/");
      setcookie('adminpassword', $password, time() + (3600 * 24), "/");
    }
    exit;
  }
}

require_once 'lib/header.php';
?>
<section class="vh-100" style="background-color: #9A616D;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="https://images.pexels.com/photos/14941160/pexels-photo-14941160.jpeg?auto=compress&cs=tinysrgb&w=1600&lazy=load" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="" method="POST">
                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0">Admin login Portal</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                  <div class="form-outline mb-4">
                    <input type="email" class="form-control" id="username" name="username" value='<?php if (isset($_COOKIE["adminusername"])) echo $_COOKIE["adminusername"] ?>' required>
                    <div class="invalid-feedback">
                      Please provide a valid email.
                    </div>
                    <label class="form-label" for="form2Example17">Email address</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="text" class="form-control" id="password" name="password" value='<?php if (isset($_COOKIE["adminpassword"])) echo $_COOKIE["adminpassword"] ?>' aria-describedby="inputGroupPrepend" required>
                    <div class="invalid-feedback">
                      Please choose a Password.
                    </div>
                    <label class="form-label" for="form2Example27">Password</label>
                  </div>
                  <div class="form-check mb-3 ">
                    <input class="form-check-input mt-2" type="checkbox" value="rememberme" id="rememberme" checked />
                    <label class="form-check-label fs-5" for="rememberme"> Remember me </label>
                  </div>

                  <div class="pt-1 mb-4">
                    <button class="btn btn-dark btn-lg btn-block" id="Login" type="button">Login</button>
                  </div>

                  <a class="small text-muted" href="#!">Forgot password?</a>
                  <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!" style="color: #393f81;">Register here</a></p>
                  <a href=" " class="small text-muted">Terms of use.</a>
                  <a href=" " class="small text-muted">Privacy policy</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  var LoginRedirectLink = "<?php echo SITE_URL ?>/PHPOPS/Application/Admin/Dashboard.php";
</script>
<script src='adminscript.js'></script>
<?php
require_once('../AdminLib/footer.php');
?>