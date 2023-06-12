<?php
session_start();
if ($_POST['action'] == 'logout') {
  unset($_SESSION['useremail']);
  unset($_SESSION['userpassword']);
  $_SESSION['SuccessfullLogout'] = 'done';
  echo "success";
  exit;
}
require_once('siteConstant.php');
require_once('Operations.php');
require_once('./sendmail.php');
require_once('./sendsms.php');

$otp = genetateotp();
setcookie("otp", $otp, time() + 600, "/");
$con = new Operations('192.168.101.102:3306', 'root', 'deep70', 'Property_DBMS');
//  $con = new Operations('localhost','root','','property_dbms');
require_once "./Ajaxcalls.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?></title><script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
  </script> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<style>
  .example::-webkit-scrollbar {
    display: none;
  }

  #full-stars-example

  /* use display:inline-flex to prevent whitespace issues. alternatively, you can put all the children of .rating-group on a single line */
  .rating-group {
    display: inline-flex;
  }

  /* make hover effect work properly in IE */
  .rating__icon {
    pointer-events: none;
  }

  /* hide radio inputs */
  .rating__input {
    position: absolute !important;
    left: -9999px !important;
  }

  /* set icon padding and size */
  .rating__label {
    cursor: pointer;
    padding: 0 0.1em;
    font-size: 2rem;
  }

  /* set default star color */
  .rating__icon--star {
    color: orange;
  }

  /* set color of none icon when unchecked */
  .rating__icon--none {
    color: #eee;
  }

  /* if none icon is checked, make it red */
  .rating__input--none:checked+.rating__label .rating__icon--none {
    color: red;
  }

  /* if any input is checked, make its following siblings grey */
  .rating__input:checked~.rating__label .rating__icon--star {
    color: #ddd;
  }

  /* make all stars orange on rating group hover */
  .rating-group:hover .rating__label .rating__icon--star {
    color: orange;

  }

  /* make hovered input's following siblings grey on hover */
  .rating__input:hover~.rating__label .rating__icon--star {
    color: #ddd;
  }

  /* make none icon grey on rating group hover */
  .rating-group:hover .rating__input--none:not(:hover)+.rating__label .rating__icon--none {
    color: #eee;
  }

  /* make none icon red on hover */
  .rating__input--none:hover+.rating__label .rating__icon--none {
    color: red;
  }
</style>

<body>