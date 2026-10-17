<?php
include_once("inc/db.php");

// Function to view students --------------------------------------------------------------------------//

function view_std() {
    global $con;
    $query = "SELECT u.*, (SELECT COUNT(*) FROM enrollments AS e WHERE e.UserID = u.UserID) AS enrolled_num 
              FROM users AS u 
              WHERE u.UserType = 'Student' 
              ORDER BY fname";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $i = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $i++ . "</td>
                <td>" . $row['fname'] . ' ' . $row['lname'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $row['enrolled_num'] . "</td>
              </tr>";
    }
}

// Function to view teachers ------------------------------------------------------------------------//

function view_teacher() {
    global $con;
    $query = "SELECT u.*, 
                     (SELECT COUNT(*) FROM courses AS c WHERE c.submitted = 1 AND c.UserID = u.UserID) AS published, 
                     (SELECT COALESCE(SUM(e.price), 0) FROM enrollments AS e WHERE u.UserID = e.TID) AS price 
              FROM users AS u 
              WHERE u.UserType = 'Tutor' 
              ORDER BY fname";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $i = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $i++ . "</td>
                <td>" . $row['fname'] . ' ' . $row['lname'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>$" . ((40) / 100) * ($row['price']) . "</td>
                <td>" . $row['published'] . "</td>
              </tr>";
    }
}

// Function to view active courses -----------------------------------------------------------------//

function view_active_courses() {
    global $con;
    $query = "SELECT c.name, c.thumbnail, 
                     (SELECT COUNT(*) FROM tasks AS t WHERE t.CourseID = c.CourseID) AS lectures_num, 
                     (SELECT COALESCE(SUM(e.price), 0) FROM enrollments AS e WHERE e.CourseID = c.CourseID) AS price, 
                     u.fname, u.lname 
              FROM courses AS c 
              JOIN users AS u ON u.UserID = c.UserID 
              WHERE c.submitted = 1 AND c.approved = 1";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $i = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $i++ . "</td>
                <td><img class='card-img-top' src='../img/thumbnails/" . $row['thumbnail'] . "'/></td>
                <td>" . $row['name'] . "</td>
                <td>" . $row['lectures_num'] . "</td>
                <td>$" . ((60) / 100) * ($row['price']) . "</td>
                <td>" . $row['fname'] . ' ' . $row['lname'] . "</td>
              </tr>";
    }
}

// Function to view pending courses ----------------------------------------------------------------//

function view_pending() {
    global $con;
    $query = "SELECT c.name, c.thumbnail, c.CourseID, 
                     (SELECT COUNT(*) FROM tasks AS t WHERE t.CourseID = c.CourseID) AS lectures_num, 
                     u.fname, u.lname 
              FROM courses AS c 
              JOIN users AS u ON u.UserID = c.UserID 
              WHERE c.submitted = 1 AND c.approved = 0";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $i = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $i++ . "</td>
                <td><img class='card-img-top' src='../img/thumbnails/" . $row['thumbnail'] . "'/></td>
                <td>" . $row['name'] . "</td>
                <td>" . $row['lectures_num'] . "</td>
                <td>" . $row['fname'] . ' ' . $row['lname'] . "</td>
                <td><a href='index.php?pending&approve=" . $row["CourseID"] . "'><i class='fa fa-check-circle approve-icon' aria-hidden='true'></i></a></td>
                <td><a href='#' class='reject-icon' onclick='openRejectModal(" . $row["CourseID"] . ")'><i class='fa fa-times-circle' aria-hidden='true'></i></a></td>
              </tr>";
    }
}

// Function to reject a course --------------------------------------------------------------------//

function reject_course($CourseID, $reason) {
    global $con;
    try {
        $sql = "UPDATE courses SET approved = -1, rejected = 1, rejection_reason = :reason WHERE CourseID = :CourseID";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":reason", $reason, PDO::PARAM_STR);
        $stmt->bindParam(":CourseID", $CourseID, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

// Function to approve a course -------------------------------------------------------------------//

function approve_course() {
    if (isset($_GET['approve'])) {
        global $con;
        $CourseID = $_GET["approve"];
        try {
            $sql = "UPDATE courses SET approved = 1 WHERE CourseID = :CourseID";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":CourseID", $CourseID, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
}

// Function to view feedback ----------------------------------------------------------------------//

function view_feedback() {
    global $con;
    $query = "SELECT * FROM feedback";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $i = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $i++ . "</td>
                <td>" . $row['name'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $row['msg'] . "</td>
              </tr>";
    }
}

// Function to view the number of students --------------------------------------------------------//

function view_num_students() {
    global $con;
    $query = "SELECT COUNT(*) AS student_num FROM users WHERE UserType = 'Student'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row["student_num"];
}

// Function to view the number of teachers --------------------------------------------------------//

function view_num_teachers() {
    global $con;
    $query = "SELECT COUNT(*) AS teachers_num FROM users WHERE UserType = 'Tutor'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row["teachers_num"];
}

// Function to view the number of courses ---------------------------------------------------------//

function view_num_courses() {
    global $con;
    $query = "SELECT COUNT(*) AS course_num FROM courses WHERE submitted = 1 AND approved = 1";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row["course_num"];
}

// Function to view the number of pending courses -------------------------------------------------//

function view_num_pending_courses() {
    global $con;
    $query = "SELECT COUNT(*) AS pending_course_num FROM courses WHERE submitted = 1 AND approved = 0";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row["pending_course_num"];
}

// Function to view the number of feedback entries ------------------------------------------------//

function view_num_feedback() {
    global $con;
    $query = "SELECT COUNT(*) AS feedback_num FROM feedback";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row["feedback_num"];
}

// Function to view profit -----------------------------------------------------------------------//

function view_profit() {
    global $con;
    $query = "SELECT COALESCE(SUM(price), 0) AS price FROM enrollments";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo ceil(((60) / 100) * ($row["price"]));
}

// Function to count rewards ---------------------------------------------------------------------//

function count_rewards() {
    global $con;

    if (!$con) {
        return "<span style='color:red;'>DB Connection Error</span>";
    }

    try {
        $query = "SELECT COUNT(*) AS total FROM rewards";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['total']) ? $row['total'] : 0;
    } catch (Exception $e) {
        return "<span style='color:red;'>Error: " . $e->getMessage() . "</span>";
    }
}

// Function to view rewards ----------------------------------------------------------------------//

function view_rewards(): string {
    global $con;

    // Fetch all rewards with course details
    $query = "SELECT r.reward_id, r.points_required, c.CourseID, c.name, c.thumbnail 
              FROM rewards r 
              JOIN courses c ON r.CourseID = c.CourseID";
    $stmt = $con->prepare($query);
    $stmt->execute();

    $html = '';

    // Add reward form with course dropdown (POST submission to same page)
    $html .= '<form method="POST">
                <tr>
                    <td>
                        <select name="CourseID" required>
                            <option value="">Select a Course</option>';
                            // Fetch all available courses
                            $get_courses = $con->prepare("SELECT CourseID, name FROM courses");
                            $get_courses->execute();
                            while ($course = $get_courses->fetch(PDO::FETCH_ASSOC)) {
                                $html .= "<option value='" . $course['CourseID'] . "'>" . $course['name'] . "</option>";
                            }
    $html .= '      </select>
                    </td>
                    <td><input type="number" name="points_required" placeholder="Points Required" required></td>
                    <td><button type="submit" name="add_reward">Add Reward</button></td>
                </tr>
              </form>';

    // Display existing rewards
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= '<tr>';
        $html .= '<td>
                    <img src="../img/thumbnails/' . $row['thumbnail'] . '" width="50" height="50"><br>
                    ' . $row['name'] . '
                  </td>';
        $html .= '<td>' . $row['points_required'] . '</td>';
        $html .= '<td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="reward_id" value="' . $row['reward_id'] . '">
                        <button type="submit" name="delete_reward" style="color:red;">Delete</button>
                    </form>
                  </td>';
        $html .= '</tr>';
    }

    return $html;
}

function view_num_unique_categories() {
    global $con;
    $query = "SELECT COUNT(DISTINCT Category) AS unique_categories FROM courses";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["unique_categories"];
}
// Function to add a category ----------------------------------------------------------------------//

function add_cat() {
    global $con;
    if (isset($_POST['add_cat'])) {
        $cat_name = $_POST['cat_name'];
        try {
            $query = "INSERT INTO courses (Category) VALUES (:cat_name)";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":cat_name", $cat_name, PDO::PARAM_STR);
            $stmt->execute();
            return "<div class='success'>Category added successfully!</div>";
        } catch (PDOException $e) {
            return "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }
    }
    return "";
}

// Function to edit a category --------------------------------------------------------------------//

function edit_cat() {
    global $con;
    if (isset($_GET['edit_cat'])) {
        $cat_id = $_GET['edit_cat'];
        if (isset($_POST['edit_cat'])) {
            $new_cat_name = $_POST['cat_name'];
            try {
                $query = "UPDATE courses SET Category = :cat_name WHERE CourseID = :cat_id";
                $stmt = $con->prepare($query);
                $stmt->bindParam(":cat_name", $new_cat_name, PDO::PARAM_STR);
                $stmt->bindParam(":cat_id", $cat_id, PDO::PARAM_INT);
                $stmt->execute();
                return "<div class='success'>Category updated successfully!</div>";
            } catch (PDOException $e) {
                return "<div class='error'>Error: " . $e->getMessage() . "</div>";
            }
        }

        // Fetch the category for editing
        $query = "SELECT Category FROM courses WHERE CourseID = :cat_id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(":cat_id", $cat_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if row is false (meaning category not found)
        if (!$row) {
            return "<div class='error'>Error: Category not found.</div>";
        }

        return "
        <div id='bodyright'>
            <h3>Edit Category</h3>
            <div id='add'>
                <form method='post' enctype='multipart/form-data'>
                    <input type='text' name='cat_name' value='" . htmlspecialchars($row['Category'], ENT_QUOTES) . "' required='required' />
                    <center><button name='edit_cat'>Update Category</button></center>
                </form>
            </div>
        </div>";
    }
    return "";
}


// Function to view all categories ----------------------------------------------------------------//

function view_cat() {
    global $con;
    $query = "SELECT DISTINCT Category FROM courses";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $i = 1;
    $html = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= "<tr>
                    <td>" . $i++ . "</td>
                    <td>" . $row['Category'] . "</td>
                    <td><a href='index.php?cat&edit_cat=" . $row['Category'] . "'>Edit</a></td>
                    <td><a href='index.php?cat&delete_cat=" . $row['Category'] . "' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a></td>
                  </tr>";
    }
    return $html;
}

// Function to delete a category ------------------------------------------------------------------//

function delete_cat() {
    global $con;
    if (isset($_GET['delete_cat'])) {
        $cat_name = $_GET['delete_cat'];
        try {
            $query = "DELETE FROM courses WHERE Category = :cat_name";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":cat_name", $cat_name, PDO::PARAM_STR);
            $stmt->execute();
            return "<div class='success'>Category deleted successfully!</div>";
        } catch (PDOException $e) {
            return "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }
    }
    return "";
}
?>