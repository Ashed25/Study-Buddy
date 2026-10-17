

<?php
function fileupload($file,$dir){
	$target_dir = $dir;
	$target_user_file = $target_dir . basename($file["name"]);
	$imageFileType = strtolower(pathinfo($target_user_file,PATHINFO_EXTENSION));
	$uploadOk = 1;
	$file_name=md5(uniqid(rand(), true)).".".$imageFileType;
	$target_file = $target_dir . $file_name;
	if (file_exists($target_file)) {
	  echo "Sorry, file already exists.";
	  $uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	  echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  if (move_uploaded_file($file["tmp_name"], $target_file)) {
	    return $file_name;
	  } else {
	    return false;
	  }
	}
}
function add_course()
{
    if (isset($_POST['title'])) { // Ensure form input matches the function
        include("../admin/inc/db.php");

        $imgname = fileupload($_FILES["thumbnail"], "../img/thumbnails/");
        if ($imgname) {
            $UID = $_SESSION["UserID"];
            $name = $_POST["title"];
            $sub_cat_id = $_POST["sub_cat_id"]; // Ensure correct column name
            $price = $_POST["price"];
            $description = $_POST["decription"];
            $level = $_POST["level"];

            try {
                $sql = "INSERT INTO courses (name, CategoryID, thumbnail, UserID, price, decription, level, submitted, approved, rejected, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 'Pending')";
                $stmt = $con->prepare($sql);
                $stmt->execute([$name, $sub_cat_id, $imgname, $UID, $price, $description, $level]);

                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('Course added successfully!');</script>";
                } else {
                    echo "<script>alert('Failed to add course.');</script>";
                }
            } catch (PDOException $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading file.');</script>";
        }
    }
}


function select_cat(){
	include("../admin/inc/db.php");
	$get_cat=$con->prepare("select * from categories");
	$get_cat->execute();
 
	while ($row=$get_cat->fetch(PDO::FETCH_ASSOC)):
	echo"<option value='".$row['Category']."'>".$row['Category']."</option>";
	endwhile;
}

function select_sub_cat(){
    include("../admin/inc/db.php");
    $get_cat = $con->prepare("SELECT * FROM categories ORDER BY Category");
    $get_cat->execute();
 
    while ($row=$get_cat->fetch(PDO::FETCH_ASSOC)):
    echo '<option value='. $row['CategoryID'] . '">' . $row['Category'] . '</option>';
    endwhile;
}

function view_course(){
    include("../admin/inc/db.php");
    $UID = $_SESSION["UserID"];

    $get_cat = $con->prepare("SELECT c.CourseID, c.name, c.thumbnail, c.price, 
                                     c.submitted, c.approved, c.rejected, c.status, 
                                     cat.Category AS category_name, c.rejection_reason
                              FROM courses AS c 
                              JOIN categories AS cat ON c.CategoryID = cat.CategoryID 
                              WHERE c.UserID = :uid");

    $get_cat->bindParam(":uid", $UID, PDO::PARAM_INT);
    $get_cat->execute();
    $i = 1;

    while ($row = $get_cat->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $i++ . "</td>
                <td><img class='thumbnail' src='../img/thumbnails/" . $row['thumbnail'] . "'></td>
                <td>" . $row['name'] . "</td>
                <td>" . ($row['category_name'] ?? '-') . "</td>  <!-- Fixes undefined key -->
                <td>" . ($row['price'] ? $row['price'] : "Free") . "</td>
                <td>";

        if ($row['submitted'] == 1 && $row['approved'] == 1) {
            echo "<span style='color:green; font-weight:bold;'>Published</span>";
        } elseif ($row['submitted'] == 1 && $row['approved'] == 0) {
            echo "<span style='color:blue; font-weight:bold;'>Pending</span>";
        } elseif ($row['rejected'] == 1) {
            echo "<span style='color:red; font-weight:bold;'>Rejected</span>";
            if (!empty($row['rejection_reason'])) {
                echo "<br><small>Reason: " . htmlspecialchars($row['rejection_reason']) . "</small>";
            }
        } else {
            echo "<span style='color:black; font-weight:bold;'>Not Submitted</span>";
        }

        echo "</td>
              <td><a href='addlec.php?id=" . $row["CourseID"] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></td>
              <td><a href='index.php?submit=" . $row["CourseID"] . "'><i class='fa fa-check-circle' aria-hidden='true'></i></a></td>
             </tr>";
    }
}

 
 



function view_lectures(){
	include("../admin/inc/db.php");

	$CID = isset($_GET["id"]) ? $_GET["id"] : null;
	if (!$CID) {
		echo "<tr><td colspan='4'>Invalid Course ID.</td></tr>";
		return;
	}

	$get_cat = $con->prepare("SELECT TaskID, Title, Video, TaskOrder FROM tasks WHERE CourseID = :CID ORDER BY TaskOrder ASC");
	$get_cat->bindParam(":CID", $CID, PDO::PARAM_INT);
	$get_cat->execute();

	// Debugging: Print results
	$results = $get_cat->fetchAll(PDO::FETCH_ASSOC);
	if (empty($results)) {
		echo "<tr><td colspan='4'>No lectures found.</td></tr>";
		return;
	}

	$i = 1;
	foreach ($results as $row) {
		echo "<tr>
				<td>" . $i++ . "</td>
				<td>" . htmlspecialchars($row['Title']) . "</td>
				<td> <video height='150' controls>
					<source src='../video/" . htmlspecialchars($row['Video']) . "' type='video/mp4'>
					Your browser does not support the video tag.
				</video> </td>
				<td>" . $row['TaskOrder'] . "</td>
			  </tr>";
	}
}


	function add_lectures()
	{
		if (isset($_POST['title'])) {
			include("../admin/inc/db.php");
	
			$CID = isset($_GET["id"]) ? $_GET["id"] : null; 
			if (!$CID) {
				die("Invalid Course ID.");
			}
	
			$videoname = fileupload($_FILES["video"], "../video/");
			if ($videoname) {
				$title = $_POST["title"];
				$lec_no = $_POST["lec_no"];
	
				try {
					$sql = "INSERT INTO tasks (CourseID, Title, Video, TaskOrder) VALUES (:CID, :Title, :Video, :TaskOrder)";
					$stmt = $con->prepare($sql);
					$stmt->bindParam(":CID", $CID, PDO::PARAM_INT);
					$stmt->bindParam(":Title", $title, PDO::PARAM_STR);
					$stmt->bindParam(":Video", $videoname, PDO::PARAM_STR);
					$stmt->bindParam(":TaskOrder", $lec_no, PDO::PARAM_INT);
					$stmt->execute();
	
					if ($stmt->rowCount() > 0) {
						echo "Lecture added successfully!";
					} else {
						echo "Failed to add lecture.";
					}
				} catch (PDOException $e) {
					die("Error: " . $e->getMessage());
				}
			} else {
				die("Error uploading file.");
			}
		}
	}
	
	

function submit_course(){
	if (isset($_GET['submit'])) {
		include("../admin/inc/db.php");
		$CID=$_GET["submit"];
		try {
		$sql = "UPDATE courses SET submitted=1 WHERE CourseID='$CID'";
		  $con->exec($sql);
		} catch(PDOException $e) {
		  echo $sql . "<br>" . $e->getMessage();
		}
	}
}
function view_total_fees() {
    include("../admin/inc/db.php"); // Ensure the database connection is included
    $UID = $_SESSION["UserID"];

    $get_balance = $con->prepare("SELECT balance FROM users WHERE UserID = ? AND UserType = 'Tutor'");
    $get_balance->execute([$UID]);
    $row = $get_balance->fetch(PDO::FETCH_ASSOC);

    return number_format($row["balance"], 2); // Format to 2 decimal places
}

function view_fees(){
	include("../admin/inc/db.php");
	$UID=$_SESSION["UserID"];
	$get_cat=$con->prepare("select COALESCE(SUM(e.price),0) as total,COUNT(e.EnrollmentID) as enrolled_num,e.price,c.name,c.thumbnail from enrollments as e, courses as c WHERE e.CourseID=c.CourseID and `TID`='$UID' GROUP BY e.CourseID");
	$get_cat->execute();
	$i=1;
	while($row=$get_cat->fetch(PDO::FETCH_ASSOC)):
		echo"<tr>
			<td>".$i++."</td>
			<td><img class='thumbnail' src='../img/thumbnails/".$row['thumbnail']."'></td>
			<td>".$row['name']."</td>
			<td>".$row['price']."</td>
			<td>".$row['enrolled_num']."</td>
			<td>".((40)/100)*($row['price'])."</td>
			<td>".((40)/100)*($row['total'])."</td>
		</tr>";
	endwhile;

}


function update_teacher_balance($teacher_id, $amount) {
    global $con;
    $query = "UPDATE users SET balance = balance + :amount WHERE UserID = :teacher_id AND UserType = 'Tutor'";
    $stmt = $con->prepare($query);
    $stmt->bindParam(":amount", $amount, PDO::PARAM_STR);
    $stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
    $stmt->execute();
}

function enroll_student($CID, $UID, $price, $TID) {
    global $con;
    try {
        $query = "INSERT INTO enrolled (CourseID, UserID, price, TID) VALUES (:CourseID, :UserID, :price, :TID)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(":CourseID", $CID, PDO::PARAM_INT);
        $stmt->bindParam(":UserID", $UID, PDO::PARAM_INT);
        $stmt->bindParam(":price", $price, PDO::PARAM_STR);
        $stmt->bindParam(":TID", $TID, PDO::PARAM_INT);
        $stmt->execute();

        // Store 40% of the price as the tutor's earnings
        $earnings = $price * 0.40;
        update_teacher_balance($TID, $earnings);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

?>