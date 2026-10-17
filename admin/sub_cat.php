<?php echo add_sub_cat(); ?>

<div id="bodyright">
    <?php if (isset($_GET['edit_sub_cat'])) { echo edit_sub_cat(); } else { ?>
    <h3>View All Categories</h3>
    <div id="add">
        <details>
            <summary>Add Category</summary>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="sub_cat_name" placeholder="Enter New Category" required="required" />
                <center><button name="add_sub_cat">Add Category</button></center>
            </form>
        </details>
        <table>
            <tr>
                <th>Sr No.</th>
                <th>Category Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php echo view_sub_cat(); ?>
        </table>
    </div>
    <?php } ?>
</div>