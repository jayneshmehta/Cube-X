<?php
session_start();
$title = "Cube-X";
if($_SESSION['Signin'] == "SignSuccessfull"){
  $Signsuccess = "<script>
  toastr.success('Sigin Successfull..pls login.. ');
  $(document).ready(function(){
     // set time out 5 sec
        setTimeout(function(){
           $('#login').trigger('click');
       }, 1000);
   });
   </script>";
   unset($_SESSION['Signin']);
}

require_once 'userlib/siteConstant.php';
require_once 'userlib/Operations.php';
require_once 'userlib/header.php';
require_once 'userlib/navbar.php';
?>
<div class="row gx-0">

<div id="bottomBtn" class="carousel carousel-dark slide w-100 p-0 " data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#bottomBtn" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#bottomBtn" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#bottomBtn" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner" style="height : 87vh;">
    <div class="carousel-item active" data-bs-interval="2000">
      <img src="https://images.pexels.com/photos/1428348/pexels-photo-1428348.jpeg?auto=compress&cs=tinysrgb&w=1600" class=" w-100 img-fluid" alt="..." >
      <div class="card-img-overlay text-center align-middle  text-light" style="background-color: rgba(0, 0, 0, 0.3)">
    <h5 class="card-title display-1  " style="margin-top: 300px;" > <strong>A varity of new <span class="text-info" >Property's</span></strong></h5>
    <p class="card-text">
      This is a wider card with supporting text below as a natural lead-in to additional
      content. This content is a little bit longer.
    </p>
    <p class="card-text">Last updated 3 mins ago</p>
  </div>
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <img src="https://images.pexels.com/photos/2029715/pexels-photo-2029715.jpeg?auto=compress&cs=tinysrgb&w=1600" class=" w-100 img-fluid " alt="...">
      <div class="card-img-overlay text-center align-middle  text-light" style="background-color: rgba(0, 0, 0, 0.3)">
    <h5 class="card-title display-1  " style="margin-top: 300px;" > <strong>World's <span class="text-danger" >No.1 </span>Property consultant. </strong></h5>
    <p class="card-text">
      This is a wider card with supporting text below as a natural lead-in to additional
      content. This content is a little bit longer.
    </p>
    <p class="card-text">Last updated 3 mins ago</p>
  </div>
  </div>
    <div class="carousel-item">
      <img src="https://images.pexels.com/photos/7464715/pexels-photo-7464715.jpeg?auto=compress&cs=tinysrgb&w=1600" class="d-block w-100 img-fluid" alt="..." >
      <div class="card-img-overlay text-center align-middle  text-light" style="background-color: rgba(0, 0, 0, 0.3)">
    <h5 class="card-title display-1  " style="margin-top: 300px;" > <strong>Explore your best <span class= "text-warning">leaving experience</span> Now in your own Cities </strong></h5>
    <p class="card-text">
      This is a wider card with supporting text below as a natural lead-in to additional
      content. This content is a little bit longer.
    </p>
    <p class="card-text">Last updated 3 mins ago</p>
  </div>
    </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#bottomBtn" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#bottomBtn" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</div>
<div class="bg-light bg-opacity-70 text-center " style ="height : 50vh ; padding-inline: 418px ">
  <p class="align-middle display-3 "  style="padding-top: 120px;" >Welcome to The <br><strong> Best Real Estate Agency <strong></p>
  <p>Estancy is a full-service, luxury real estate brokerage and lifestyle company representing clients worldwide in a broad spectrum of classes, including residential, new development, resort real estate, residential leasing and luxury vacation rentals. Since our inception in 2011, we have redefined the business of real estate, modernizing and advancing the industry by fostering a culture of partnership, in which all clients and listings are represented by our agents.</p>
<div class="d-col gap-2">
  <a href="<?php echo SITE_URL ; ?>PHPOPS/Application/showproperties.php"  type="button" name="explore" id="explore" class="btn btn-danger fs-4">Explore</a>
</div>
</div>
<div class="bg-primary bg-opacity-10 text-center " style ="height : 50vh ; padding-inline: 418px ">
<p class="align-middle display-3 text-light "  style="padding-top: 20px;" >WHAT OUR CILENTS SAY</p>
<div id="carouselIntervalbottomBtn" class="carousel slide" data-bs-ride="carousel" style="padding-inline: 60px">
  <div class="carousel-inner">
    <div class="carousel-item active p-5" data-bs-interval="10000">
      <p class="align-middle display-5 text-light " style="padding-top: 10px;">Testomonial</p>
      <img src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=1600"class="img-fluid rounded-circle p-1 border border-dark border-3 " width="100px" height="100px" style="object-fit :cover; "  alt="">
      <p class=" text-light" >Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil repellat nulla qui esse corrupti fugit excepturi quasi dicta eaque quam sint nisi, ea pariatur laboriosam consequuntur ipsam impedit distinctio explicabo! </p> 
      <p class=" text-light fs-5" ><strong>khushi kapoor</strong></p>
    </div>
    <div class="carousel-item  p-5" data-bs-interval="2000">
      <p class="align-middle display-5 text-light " style="padding-top: 10px;">Testomonial</p>
      <img src="https://images.pexels.com/photos/1542085/pexels-photo-1542085.jpeg?auto=compress&cs=tinysrgb&w=1600"class="img-fluid rounded-circle p-1 border border-dark border-3 " width="100px" height="100px" style="object-fit :cover; " alt="">
      <p class=" text-light" >Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil repellat nulla qui esse corrupti fugit excepturi quasi dicta eaque quam sint nisi, ea pariatur laboriosam consequuntur ipsam impedit distinctio explicabo! </p> 
      <p class=" text-light fs-5" ><strong>Tulsi Verma</strong></p>
    </div>
    <div class="carousel-item  p-5" data-bs-interval="2000">
      <p class="align-middle display-5 text-light " style="padding-top: 10px;">Testomonial</p>
      <img src="https://images.pexels.com/photos/3763188/pexels-photo-3763188.jpeg?auto=compress&cs=tinysrgb&w=1600"class="img-fluid rounded-circle p-1 border border-dark border-3 " width="100px" height="100px" style="object-fit :cover; " alt="">
      <p class=" text-light" >Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil repellat nulla qui esse corrupti fugit excepturi quasi dicta eaque quam sint nisi, ea pariatur laboriosam consequuntur ipsam impedit distinctio explicabo! </p> 
      <p class=" text-light fs-5" ><strong>Ayushi Dutta</strong></p>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselIntervalbottomBtn" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselIntervalbottomBtn" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</div>
<div class="row  bg-opacity-10 text-center gx-0" style ="height : 40vh ; padding-inline: 418px ; margin-top : 100px">
  <div class='col-6 ' >
    <img src="../../Assets/images/finalApplogo.png" class="img-fluid" width="300px" alt="">
    <p style="text-align: justify;" >Cube-X is a full-service, luxury real estate brokerage and lifestyle company representing clients worldwide in a broad spectrum of classes, including residential, new development, resort real estate, residential leasing and luxury vacation rentals.</p>
    <p>© 2023.  CubeX. All Right Reserved. Privacy Policy</p>
  </div>
   <div class='col-6 pt-3 ps-5' style="text-align: justify; margin-top : 40px ">
    <img src="https://www.svgrepo.com/show/513552/location-pin.svg" class='mb-3 me-4'  width="30px" alt="">
    <span class="text-danger"> 404 crossgate near rajhans cinema Ahmedabad bapod 374842 </span><br> 
    <img src="https://www.svgrepo.com/show/292192/call.svg" class='mb-3 me-4' width="30px" alt="">
    <span class="text-danger"> +91 94 6666 9595</span> <br>
    <img src="https://www.svgrepo.com/show/502395/mail.svg" class='mb-3 me-4' width="30px" alt="">
    <span class="text-danger"> info.cubeX@gmail.com </span> 
  </div>
</div>

</div>
<?php echo $Signsuccess ;?>
<!-- <script> var siteurl = "<?php //echo SITE_URL; ?>"; -->
<!-- </script> -->
<script src="userscript.js"></script>

<?php
require_once 'userlib/footer.php';
?>