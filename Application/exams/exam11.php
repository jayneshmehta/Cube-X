<?php
session_start();
    $LoginUN = "jayneshmehta";
    $LoginPass = "jaynesh@123";

    if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

      if(($username == $LoginUN) && ($password == $LoginPass)){
        $_SESSION['Username'] = $username;
        $_SESSION['Password'] = $password;
        header("Location:Home.php");
      } else{
        $error = "Enter valid username password";
      }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class='row justify-content-center '>
    <div class= 'col-6'>
     <div class="display-5 mt-5 mb-5">
        User login Form:
     </div>   
<form action="" method="post" class='border border-3 border-primary p-3'>
    <div class="mb-3">
      <label for="" class="form-label">User Name</label>
      <input type="text"
        class="form-control" name="username" id="username" aria-describedby="helpId" placeholder="" required>
      <small id="helpId" class="form-text text-muted"></small>
    </div>
    <div class="mb-3">
      <label for="" class="form-label">Password</label>
      <input type="password"
        class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="" required >
      <small id="helpId" class="form-text text-muted"></small>
    </div>
    <button type="submit" class="btn btn-primary" name='submit'>Submit</button>
</form>
<?php
echo $error;
?>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</html>
