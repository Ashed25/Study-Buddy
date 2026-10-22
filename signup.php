<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "finalproj_sec541_group2");
$message = "Successfully registered";
$message1 = "The two passwords do not match";

if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  // Only Student/Tutor can self-register; never trust the posted role
  $userType = ($_POST['level'] ?? '') === 'Tutor' ? 'Tutor' : 'Student';
  $password = $_POST['password'];
  $password2 = $_POST['cpassword'];

  if ($password == $password2) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO Users (fname, lname, UserType, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $userType, $email, $hash);

    if ($stmt->execute()) {
      echo "<script type='text/javascript'>alert('$message');
            window.location='login.php';
            </script>";
    } else {
      if (mysqli_errno($db) == 1062) {
        echo "<script>alert('Email Already Exists')</script>";
      }
    }
  } else {
    echo "<script type='text/javascript'>alert('$message1');
          window.location='signup.php';
          </script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="design/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="design/bootstrap-social/bootstrap-social.css">
  <style type="text/css">
    body {
      background-image: url("img/bg1.jpg");
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
</head>
<body>
<?php 
  //include("inc/header.php");
?>

<!--Signup form------------------------------------------------------------------------------------>
<div class="modal-dialog text-center">
  <div class="col-sm-9 main-section">
    <div class="modal-content">

      <div class="col-12 user-img">
        <img src="img/face.png">
      </div>

      <div class="col-12 form-input">
        <h3 class="form-title" style="color:#c0392b;font-family: roboto;font-size: 25px;">SIGN IN</h3><br>
      
        <form action="signup.php" method="POST" onsubmit="return check()">
          <div class="form-group">
            <input type="text" class="form-control" id="fname" required="required" name="fname" placeholder="Enter First Name">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="lname" required="required" name="lname" placeholder="Enter Last Name">
          </div>
          <div class="form-group" style="border: 1px solid #000;">
            <select name="level" required class="form-control">
              <option value="Student" selected>Student</option>
              <option value="Tutor">Teacher</option>
            </select>
          </div>
          <div class="form-group">
            <input type="email" id="email" class="form-control" required="required" name="email" placeholder="Enter Email">
          </div>
          <div style="text-align: left;color: black;font-family: roboto;font-size: 18px;">Password must contain the following:</div>
          <ul style="text-align: left;color: black;font-family: roboto;font-size: 17px;">
            <li>A <b>lowercase</b> letter</li>
            <li>A <b>capital (uppercase)</b> letter</li>
            <li>A <b>number</b></li>
            <li>Minimum <b>8 characters</b></li>
          </ul>

          <div class="form-group">
            <input type="password" class="form-control" id="pass" required="required" name="password" placeholder="Enter Password"><i></i>
          </div>
          
          <div class="form-group">
            <input type="password" class="form-control" required="required" name="cpassword" placeholder="Repeat your password">
          </div>
         
          
          <button type="submit" class="btn btn-success" name="submit">Sign Up</button>
      
        </form>            
      </div> 

      <div class="col-12 login">
        <a href="login.php"> Already have an account? Log In</a>
      </div>         
    </div>
    
  </div>
  
</div>

<?php 
  //include("inc/footer.php");
?>
<script type="text/javascript">
  function check() {
    var letters = /^[A-Za-z]+$/;
    var fullname = document.getElementById('fname').value + document.getElementById('lname').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('pass').value;
    var lowerCaseLetters = /[a-z]/g;
    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;
    
    console.log(fullname);
    if (!fullname.trim().match(letters)) {
      alert('Please enter alphabets in name');
      return false;
    }
    if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))) {
      alert('Invalid Email');        
      return false;
    }
    if (!(password.match(lowerCaseLetters)) || !(password.match(upperCaseLetters)) || !(password.match(numbers)) || !(password.length >= 8)) { 
      alert('Invalid Password');                
      return false;
    }
  }
</script>

</body>
</html>