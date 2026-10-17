
<!DOCTYPE html>
<html>
<head> 

   <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="stylesheet" href="design/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="design/bootstrap-social/bootstrap-social.css">

  <link rel="stylesheet" href="css/animate.min.css">

  <title>StudyBuddy</title>
  <style type="text/css">
    
.active, .span-item:hover{
    border-bottom: 1px solid #fff;
    padding-bottom: 2px;
    box-sizing: border-box; 
}
  </style>
</head>
<body> 
<div class="js-pride-month-gradient fixed-top" style="width: 100%; height: 0.6rem; background: rgba(0, 0, 0, 0) linear-gradient(90deg, rgb(100, 91, 83) 0%, rgb(235, 82, 82) 18.23%, rgb(247, 143, 47) 34.37%, rgb(244, 193, 81) 48.96%, rgb(82, 187, 118) 66.15%, rgb(38, 165, 215) 82.29%, rgb(224, 105, 183) 100%) repeat scroll 0% 0; ">
 </div> 


     <div class="navigation">

      <nav class="navbar navbar-expand-lg fixed-top navbar-light" style="background-color: #34ace0;justify-content: space-around; margin-top: 8px;">
        <div class="header-home">
              <a class="navbar-brand"  href="index.php">
            <img src="img/lo1.png" height="44px"  alt="StudyBuddy" style="padding-bottom: 2px;"></a> 

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            
             <div class="navbar-collapse collapse" id="navbarSupportedContent">
              <ul class="navbar-nav" style="font-size: 22px; font-family: roboto; margin-left:10vw;">
              <a class="nav-item nav-link" href="#"><span class="span-item active"></span></a>
              <a class="nav-item nav-link" href="index.php" style="color: #fff;"><span class="span-item">Hom</span>e</a>
              <a class="nav-item nav-link ml-2" href="index.php#teach" style="color: #fff;"><span class="span-item">Teach on Study</span>Buddy</a>
             



              <a class="btn btn-success" href="login.php" role="button" style="margin-left:8vw;">Login</a>
              <a class="btn btn-success" href="signup.php" role="button" style="margin-left:1vw;">SignUp</a>
              </ul>
            </div>
          </div>
       </nav>
    </div>

<script>
// Add active class to the current button (highlight it)

var btns = document.getElementsByClassName("span-item");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
  var current = document.getElementsByClassName("active");
  current[0].className = current[0].className.replace(" active", "");
  this.className += " active";
  });
}
</script>
</body>
</html>