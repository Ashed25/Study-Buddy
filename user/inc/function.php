<?php
function view_course(){
  include("../admin/inc/db.php");
  $get_cat=$con->prepare("select c.*,categories.Category,u.fname,u.lname from courses as c,categories, users as u WHERE categories.CategoryID=c.CategoryID AND c.UserID=u.UserID AND c.submitted=1 and c.approved=1");
  $get_cat->execute();
  $i=1;
  while ($row=$get_cat->fetch(PDO::FETCH_ASSOC)):
    echo '<div class="card wow fadeInUp"><a href="description.php?id='.$row['CourseID'].'">
      <img class="card-img-top" src="../img/thumbnails/'.$row['thumbnail'].'" alt="Card image cap">
      <div class="card-body">
      <h5 class="card-title">'.$row['name'].'</h5>
      <p class="card-text">'.$row['Category'].' <br>By '.$row['fname'].' '.$row['lname'].'</p>';
   
    if(!$row['price']){ echo "<h4><span class='badge badge-warning'>Free</span></h4>";}
    else{ echo "<h4><span class='badge badge-warning'> $".$row['price']."</span></h4>";}
  echo '</div></a></div>';
  endwhile;
}

function view_course_details(){
  if (isset($_GET["id"])) {
    $id=$_GET["id"];
    # code...
    include("../admin/inc/db.php");
    $get_cat=$con->prepare("select c.*,categories.Category,u.fname,u.lname,(SELECT COUNT(l.TaskID) FROM tasks as l WHERE l.CourseID=c.CourseID) as lectures from courses as c,categories, users as u WHERE categories.CategoryID=c.CategoryID AND c.UserID=u.UserID AND c.submitted=1 AND c.CourseID='$id'");
    $get_cat->execute();
    $row=$get_cat->fetch(PDO::FETCH_ASSOC);
    echo '<div class="course-desciption-holder">
               <div class="upper">
                <div class="lhs">
                  <div class="course-img">
                    <img src="../img/thumbnails/'.$row['thumbnail'].'">
                  </div>
                </div>
                <div class="rhs">
                  <div class="course-details">
                    <div class="course-title">'.$row['name'].'</div>
                    <table class="course-details-list">
                      <tr>
                        <td class="course-details-name">Instructor :</td>
                        <td>'.$row['fname'].' '.$row['lname'].'</td>
                      </tr>
                      <tr>
                        <td class="course-details-name">Category :</td>
                        <td>'.$row['Category'].'</td>
                      </tr>
                      <tr>
                        <td class="course-details-name">Level :</td>
                        <td>'.$row['level'].'</td>
                      </tr>
                  
                    </table>
                    <div class="course-price-details">
                    ';
     
      if(!$row['price']){ echo '<span class="price">Free</span>';}
      else{ echo '<span class="price">Price : $'.$row['price']."</span>";}}
                      echo '</div>
                      <a class="btn btn-success btn-lg btn-block buy-now" href="bill.php?id='.$id.'" role="button"">Buy now</a>
 
                  </div>
                </div>
               </div>
               <hr>
               <div class="description-holder">'.$row['decription'].'
               </div>  
              </div>';
 
  }
 

function enrole_course(){
  if (isset($_GET["enrole"])) {
      $id = $_GET["enrole"];
      $price = $_POST["price"];
      $TID = $_GET["TID"];
      $UID = $_SESSION["UserID"];

      include("../admin/inc/db.php");
      try {
          // Insert enrollment record
          $sql = "INSERT INTO enrollments (CourseID, UserID, price, TID)
                  VALUES ('$id', '$UID', '$price', '$TID')";
          $con->exec($sql);

          // Update teacher balance (Assuming 40% commission)
          $earnings = $price * 0.40;
          $updateQuery = "UPDATE users SET balance = balance + ? WHERE UserID = ?";
          $stmt = $con->prepare($updateQuery);
          $stmt->execute([$earnings, $TID]);

          // Award points after enrollment
          award_points_on_enrollment($UID);

          echo "<script>alert('Thanks for Enrolling in the course. HAPPY LEARNING !!!')</script>";
          echo "<script>window.location.replace('mycourses.php');</script>";
      } catch(PDOException $e) {
          echo $sql . "<br>" . $e->getMessage();
      }
  }
}

function show_bill($studentID) {
  if (isset($_GET["id"])) {
      $id = $_GET["id"];
      include("../admin/inc/db.php");
      $UID = $_SESSION['UserID'];

      // Fetch course details
      $get_cat = $con->prepare("
          SELECT c.*, cat.Category, u.fname, u.lname, 
                 (SELECT COUNT(t.TaskID) FROM tasks t WHERE t.CourseID = c.CourseID) as tasks 
          FROM courses c
          JOIN categories cat ON c.CategoryID = cat.CategoryID
          JOIN users u ON c.UserID = u.UserID
          WHERE c.submitted = 1 AND c.CourseID = :id
      ");
      $get_cat->bindParam(':id', $id);
      $get_cat->execute();
      $row = $get_cat->fetch(PDO::FETCH_ASSOC);

      // Check if student is already enrolled
      $check_enrollment = $con->prepare("SELECT * FROM enrollments WHERE CourseID = ? AND UserID = ?");
      $check_enrollment->execute([$id, $UID]);
      $is_enrolled = $check_enrollment->rowCount() > 0;

      // Calculate the final price
      $final_price = !empty($row['price']) ? $row['price'] : 0;

      echo '
      <div class="form-group">
          <table>    
              <tr>
                  <td>Original price:</td>
                  <td>$' . number_format($final_price, 2) . '</td>
              </tr>
              <tr>
                  <td><b>Total:</b></td>
                  <td><b>$' . number_format($final_price, 2) . '</b></td>
              </tr>
          </table><br>';

      echo '<p style="text-align: left;">
              <i class="fa fa-caret-right" aria-hidden="true"></i> StudyBuddy is required by law to collect applicable transaction taxes for purchases made in certain tax jurisdictions.
            </p>
            <p style="text-align: left;">
              <i class="fa fa-caret-right" aria-hidden="true"></i> By completing your purchase, you agree to these <b>Terms of Service.</b>
            </p>
          </div>
          <hr class="solid">';

      // If the student is already enrolled, show a message instead of the button
      if ($is_enrolled) {
          echo '<button class="btn btn-secondary" disabled>Enrolled</button>';
      } else {
          // Show payment form only if the course is not free
          if ($final_price > 0) {
              echo '<form action="?enrole=' . $row['CourseID'] . '&TID=' . $row['UserID'] . '" method="post">
                      <input value="' . $final_price . '" name="price" type="hidden">
                      <div class="form-group">
                          <label for="payment_method">Choose Payment Method:</label>
                          <select class="form-control" id="payment_method" name="payment_method" required>
                              <option value="credit_card">Credit Card</option>
                              <option value="paypal">PayPal</option>
                              <option value="bank_transfer">Bank Transfer</option>
                          </select>
                      </div>
                      <button type="submit" class="btn btn-success">Pay</button>
                    </form>';
          } else {
              echo '<a href="?enrole=' . $row['CourseID'] . '&TID=' . $row['UserID'] . '" class="btn btn-primary">Enroll for Free</a>';
          }
      }
  }
}

function view_mycourse() {
  include("../admin/inc/db.php");
  $UID = $_SESSION["UserID"];

  $query = "SELECT DISTINCT c.CourseID, c.name, c.thumbnail, c.level, u.fname AS instructor
            FROM enrollments e
            JOIN courses c ON e.CourseID = c.CourseID
            JOIN users u ON c.UserID = u.UserID
            WHERE e.UserID = ?";
       
  $stmt = $con->prepare($query);
  $stmt->execute([$UID]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Fix: Use $stmt instead of $get_cat
      echo '<div class="card wow fadeInUp">
              <img class="card-img-top" src="../img/thumbnails/'.$row['thumbnail'].'" alt="Card image cap">
              <div class="card-body">
                  <h5 class="card-title">'.$row['name'].'</h5>
                  <p class="card-text">By '.$row['instructor'].'</p>
                  <a class="btn btn-primary" href="video.php?id='.$row['CourseID'].'" role="button">Start</a>
              </div>
            </div>';
  }
}

function view_lecture() {
  global $con, $cid, $UID;
  if (isset($_GET['video_id'])) {
      $video_id = $_GET['video_id'];
      $get_video = $con->prepare("SELECT * FROM tasks WHERE TaskID = ? AND CourseID = ?");
      $get_video->execute([$video_id, $cid]);
      if ($row = $get_video->fetch(PDO::FETCH_ASSOC)) {
          echo "<h3>" . $row["Title"] . "</h3>";
          echo "<video width='100%' height='auto' controls><source src='../video/" . $row["Video"] . "' type='video/mp4'></video>";
         
          // Check completion status
          $check_progress = $con->prepare("SELECT * FROM user_task WHERE TaskID = ? AND CourseID = ? AND UserID = ?");
          $check_progress->execute([$video_id, $cid, $UID]);
          $video_progress = $check_progress->fetch(PDO::FETCH_ASSOC);
          $is_completed = $video_progress ? $video_progress['completed'] : 0;
         
          // Complete button
          if ($is_completed == 0) {
              echo "<form action='' method='POST'>
                      <input type='hidden' name='video_id' value='" . $video_id . "'>
                      <button type='submit' name='complete_video' class='btn btn-success mt-3'>Complete</button>
                    </form>";
          } else {
              echo "<button class='btn btn-secondary mt-3' disabled>Completed</button>";
          }
      } else {
          echo "<p>Lecture not found.</p>";
      }
  } else {
      echo "<p>Select a lecture to view its content.</p>";
  }
}
function view_lectures_list() {
  global $con, $cid;
  $get_lecture = $con->prepare("SELECT * FROM tasks WHERE CourseID = ? ORDER BY TaskOrder");
  $get_lecture->execute([$cid]);
  while ($row = $get_lecture->fetch(PDO::FETCH_ASSOC)) {
      echo "<li><a href='video.php?id=" . $cid . "&video_id=" . $row["TaskID"] . "'>" . $row["Title"] . "</a></li>";
  }
}

function view_cat() {
  include("../admin/inc/db.php");
  $get_cat = $con->prepare("SELECT * FROM categories ORDER BY Category");
  $get_cat->execute();
  while ($row = $get_cat->fetch(PDO::FETCH_ASSOC)) {
      echo '<a class="dropdown-item" href="viewcat.php?id=' . $row['CategoryID'] . '">' . $row['Category'] . '</a>';
  }
}

function view_cat_course(){
  include("../admin/inc/db.php");
  if (isset($_GET["id"])) {
    $cid=$_GET["id"];
    $get_cat=$con->prepare("select c.*,categories.Category,c.CategoryID, u.fname,u.lname from courses as c,categories, users as u WHERE categories.CategoryID=$cid AND categories.CategoryID=c.CategoryID AND c.UserID=u.UserID AND c.submitted=1 and c.approved=1");
    $get_cat->execute();
    while ($row=$get_cat->fetch(PDO::FETCH_ASSOC)):
      echo '<div class="card wow fadeInUp"><a href="description.php?id='.$row['CourseID'].'">
        <img class="card-img-top" src="../img/thumbnails/'.$row['thumbnail'].'" alt="Card image cap">
        <div class="card-body">
        <h5 class="card-title">'.$row['name'].'</h5>
        <p class="card-text">'.$row['Category'].'<br>By '.$row['fname'].' '.$row['lname'].'</p>';
     
      if(!$row['price']){ echo "<h4><span class='badge badge-warning'>Free</span></h4>";}
      else{ echo "<h4><span class='badge badge-warning'> $".$row['price']."</span></h4>";}
    echo '</div></a></div>';
    endwhile;
  }
}
function view_search_course(){
  include("../admin/inc/db.php");
  if (isset($_GET["s"])) {
    $search=$_GET["s"];
    $get_cat=$con->prepare("select c.*,categories.Category, c.name,u.fname,u.lname from courses as c,categories, users as u WHERE categories.CategoryID=c.CategoryID AND c.UserID=u.UserID AND c.submitted=1 and c.approved=1 AND c.name LIKE '%$search%' ");
    $get_cat->execute();
    if ($get_cat->rowCount() > 0) {
 
    while ($row=$get_cat->fetch(PDO::FETCH_ASSOC)):
      echo '<div class="card wow fadeInUp"><a href="description.php?id='.$row['CourseID'].'">
        <img class="card-img-top" src="../img/thumbnails/'.$row['thumbnail'].'" alt="Card image cap">
        <div class="card-body">
        <h5 class="card-title">'.$row['name'].'</h5>
        <p class="card-text">'.$row['Category'].'<br>By '.$row['fname'].' '.$row['lname'].'</p>';
      if(!$row['price']){ echo "<h4><span class='badge badge-warning'>Free</span></h4>";}
      else{ echo "<h4><span class='badge badge-warning'> $;".$row['price']."</span></h4>";}
    echo '</div></a></div>';
    endwhile;
    return true;
    }else{
      return false;
    }
  }
 
 
}

function view_course_name(){
  include("../admin/inc/db.php");
  if (isset($_GET["id"])) {
    $cid=$_GET["id"];
    $get_cat=$con->prepare("SELECT Category FROM categories WHERE CategoryID=$cid");
    $get_cat->execute();
    $row=$get_cat->fetch(PDO::FETCH_ASSOC);
    echo $row['Category'];
  }
 
}

function award_points_on_enrollment($UID) {
  include("../admin/inc/db.php");
  $points_awarded = 10; // Points awarded for enrolling in a course

  $update_points = $con->prepare("UPDATE users SET points = points + ? WHERE UserID = ?");
  $update_points->execute([$points_awarded, $UID]);
}

function award_points_on_completion($UID) {
  include("../admin/inc/db.php");
  $points_awarded = 10; // Points awarded for completing each task

  $update_points = $con->prepare("UPDATE users SET points = points + ? WHERE UserID = ?");
  $update_points->execute([$points_awarded, $UID]);
}

  function redeem_course($UID, $CID) {
    include("../admin/inc/db.php");

    $stmt = $con->prepare("SELECT points_required FROM rewards WHERE CourseID = ?");
    $stmt->execute([$CID]);
    $reward = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reward) {
        $required_points = $reward['points_required'];

        $stmt = $con->prepare("SELECT points FROM users WHERE UserID = ?");
        $stmt->execute([$UID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['points'] >= $required_points) {
            $new_points = $user['points'] - $required_points;
            $con->prepare("UPDATE users SET points = ? WHERE UserID = ?")->execute([$new_points, $UID]);

            $insert_enrollment = $con->prepare("INSERT INTO enrollments (CourseID, UserID, price, TID) VALUES (?, ?, 0, ?)");
            $insert_enrollment->execute([$CID, $UID, 0]);

            echo "<script>alert('Reward redeemed successfully! Course added to My Courses.')</script>";
            echo "<script>window.location.replace('mycourses.php');</script>";
        } else {
            echo "<script>alert('Insufficient points to redeem this reward.')</script>";
        }
    }
  }

  function view_rewards() {
    global $con;
    $UID = $_SESSION['UserID'];
   
    // Fetch student points
    $get_points = $con->prepare("SELECT points FROM users WHERE UserID = ?");
    $get_points->execute([$UID]);
    $student_points = $get_points->fetch(PDO::FETCH_ASSOC)['points'];
   
    // Fetch available rewards
    $query = "SELECT r.reward_id, r.points_required, c.CourseID, c.name, c.thumbnail
              FROM rewards r
              JOIN courses c ON r.CourseID = c.CourseID";
    $stmt = $con->prepare($query);
    $stmt->execute();
   
    echo '<h3 class="text-center">Available Rewards</h3>';
    echo '<p class="text-center"><strong>Your Current Points:</strong> ' . $student_points . '</p>';
    echo '<div class="row">';
   
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagePath = "../img/thumbnails/" . htmlspecialchars($row['thumbnail']);
   
        // Check if the student has already redeemed this reward
        $check_redemption = $con->prepare("SELECT * FROM enrollments WHERE CourseID = ? AND UserID = ?");
        $check_redemption->execute([$row['CourseID'], $UID]);
        $is_redeemed = $check_redemption->rowCount() > 0;
   
        // Check if the student has enough points
        $can_redeem = $student_points >= $row['points_required'];
   
        echo '<div class="col-md-4">
                <div class="card">
                    <img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                        <p>Required Points: ' . $row['points_required'] . '</p>';
       
        if ($is_redeemed) {
            echo '<button class="btn btn-secondary" disabled>Already Redeemed</button>';
        } elseif ($can_redeem) {
            echo '<a href="redeem.php?cid=' . $row['CID'] . '" class="btn btn-primary">Redeem</a>';
        } else {
            echo '<button class="btn btn-secondary" disabled>Not Enough Points</button>';
        }
   
        echo '</div></div></div>';
    }
   
    echo '</div>';
  }
  ?>
   