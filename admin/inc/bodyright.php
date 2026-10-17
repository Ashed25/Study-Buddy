<?php
// Load the appropriate page based on the $_GET parameter
if (isset($_GET['cat'])) {
    include("cat.php");
} elseif (isset($_GET['sub_cat'])) {
    include("sub_cat.php");
} elseif (isset($_GET['contact'])) {
    include("contact.php");
} elseif (isset($_GET['view_std'])) {
    include("view_std.php");
} elseif (isset($_GET['faqs'])) {
    include("faqs.php");
} elseif (isset($_GET['active'])) {
    include("active.php");
} elseif (isset($_GET['msg'])) {
    include("msg.php");
} elseif (isset($_GET['pending'])) {
    include("pending.php");
} elseif (isset($_GET['teacher'])) {
    include("teacher.php");
} elseif (isset($_GET['pay'])) {
    include("pay.php");
} elseif (isset($_GET['rewards'])) {
    include("rewards.php");
} else {
    // Default content (e.g., dashboard)
?>
<div id="bodyright">
    <h3>Overview</h3>
    <div id="homebody">
        <div class="fees">
            <i class="fa fa-money" aria-hidden="true"></i>
            <h2>$ <?php echo view_profit(); ?></h2>
            <p>Total Profit</p>
        </div>

        <div class="body-flex-box">   
            <!-- Total Unique Categories -->
            <div class="box-holder">
                <div class="box">
                    <div class="box-upper">
                        <div class="box-lhs">
                            <div class="box-num"><?php echo view_num_unique_categories(); ?></div>
                            <div class="box-name">Total Categories</div>
                        </div> 
                        <div class="box-rhs">
                            <div class="icon"><i class="fa fa-th-large" aria-hidden="true"></i></div>
                        </div>						
                    </div>
                    <a href="index.php?cat" class="view-more">View More</a>
                </div>
            </div>

            <!-- Total Students -->
            <div class="box-holder">
                <div class="box">
                    <div class="box-upper">
                        <div class="box-lhs">
                            <div class="box-num"><?php echo view_num_students(); ?></div>
                            <div class="box-name">Total Students</div>
                        </div>
                        <div class="box-rhs">
                            <div class="icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
                        </div>						
                    </div>
                    <a href="index.php?view_std" class="view-more">View More</a>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="box-holder">
                <div class="box">
                    <div class="box-upper">
                        <div class="box-lhs">
                            <div class="box-num"><?php echo view_num_teachers(); ?></div>
                            <div class="box-name">Total Teachers</div>
                        </div>
                        <div class="box-rhs">
                            <div class="icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                        </div>						
                    </div>
                    <a href="index.php?teacher" class="view-more">View More</a>
                </div>
            </div>

            <!-- Active Courses -->
            <div class="box-holder">
                <div class="box">
                    <div class="box-upper">
                        <div class="box-lhs">
                            <div class="box-num"><?php echo view_num_courses(); ?></div>
                            <div class="box-name">Active Courses</div>
                        </div>
                        <div class="box-rhs">
                            <div class="icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                        </div>						
                    </div>
                    <a href="index.php?active" class="view-more">View More</a>
                </div>
            </div>

            <!-- Unpublished Courses -->
            <div class="box-holder">
                <div class="box">
                    <div class="box-upper">
                        <div class="box-lhs">
                            <div class="box-num"><?php echo view_num_pending_courses(); ?></div>
                            <div class="box-name">Unpublished Courses</div>
                        </div>
                        <div class="box-rhs">
                            <div class="icon"><i class="fa fa-spinner" aria-hidden="true"></i></div>
                        </div>						
                    </div>
                    <a href="index.php?pending" class="view-more">View More</a>
                </div>
            </div>

            <!-- Total Rewards -->
            <div class="box-holder">
                <div class="box">
                    <div class="box-upper">
                        <div class="box-lhs">
                            <div class="box-num"><?php echo count_rewards(); ?></div>
                            <div class="box-name">Total Rewards</div>
                        </div>
                        <div class="box-rhs">
                            <div class="icon"><i class="fa fa-gift" aria-hidden="true"></i></div>
                        </div>						
                    </div>
                    <a href="index.php?rewards" class="view-more">View More</a>
                </div>
            </div> 
        </div>		
    </div>
</div> 
<?php } ?>