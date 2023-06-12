<div class='row gx-0'>
    <div class="col-sm-2 border-end border-2 border-success  ">
        <div class="my-auto " style=" height:75vh ; ">
            <a type="button" class="d-flex text-decoration-none d-flex justify-content-center mt-2 pt-2 border-start border-success border-5" href='<?php echo SITE_URL; ?>PHPOPS/Application/Admin/'>
                <img src="<?php echo SITE_URL; ?>Assets/images/dashboard.png" width='40px' height='40px' alt="">
                <p class='ms-3 fs-5 text-success mt-1'>Dashboard</p>
            </a>
            <a type="button" class="d-flex text-decoration-none d-flex justify-content-center mt-2 pt-2 " href='logion.php'>
                <img src="<?php echo SITE_URL; ?>Assets/images/home.png" width='40px' height='40px' alt="">
                <p class='ms-3 fs-5 text-success mt-1'>Properties</p>
            </a>
            <a type="button" class="d-flex text-decoration-none d-flex justify-content-center mt-2 me-3 pt-2 mb-auto " href='logion.php'>
                <img src="<?php echo SITE_URL; ?>Assets/images/user.png" width='30px' height='30px' alt="">
                <p class='ms-5 fs-5 text-success mt-1'>Users</p>
            </a>
        </div>
        <hr>
        <div>
            <div class="dropup-center dropup d-flex justify-content-center">
                <button class="btn  dropdown-toggle px-2 pe-4   " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class='me-3' src="<?php echo SITE_URL; ?>Assets/images/adminprofile.png" width='40px' height='40px' alt="">
                    Setting
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item text-success text-center" href="#" id='addadmin'>Add admin</a></li>
                    <li><button class="dropdown-item btn btn-outline-danger  text-center" href="#" id='logout'>Logout</button></li>
                </ul>
            </div>
        </div>
    </div>