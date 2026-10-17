<?php 
function view_search_course() {
  include("admin/inc/db.php");

  if (isset($_GET["s"])) {
    $search = $_GET["s"];
    
    // Updated query: Use `Users` and `Courses` tables, and remove joins with `cat` and `sub_cat`
    $get_cat = $con->prepare("
      SELECT c.*, u.fname, u.lname 
      FROM Courses AS c 
      INNER JOIN Users AS u ON c.UserID = u.UserID 
      WHERE c.submitted = 1 AND c.approved = 1 
      AND (c.name LIKE '%$search%' OR c.description LIKE '%$search%')
    ");
    $get_cat->execute();

    if ($get_cat->rowCount() > 0) {
      while ($row = $get_cat->fetch(PDO::FETCH_ASSOC)):
        echo '<div class="card wow fadeInUp"><a href="signup.php">
          <img class="card-img-top" src="img/thumbnails/' . $row['thumbnail'] . '" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">' . $row['name'] . '</h5>
            <p class="card-text">' . $row['Category'] . '<br>By ' . $row['fname'] . ' ' . $row['lname'] . '</p>';
        
        if (!$row['price']) {
          echo "<h4><span class='badge badge-warning'>Free</span></h4>";
        } else {
          echo "<h4><span class='badge badge-warning'> $" . $row['price'] . "</span></h4>";
        }
        
        echo '</div></a></div>';
      endwhile;
      return true;
    } else {
      return false;
    }
  }
}



 ?>