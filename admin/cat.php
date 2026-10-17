<?php
echo add_cat();
echo delete_cat();
?>

<div id="bodyright">
    <?php
    if (isset($_GET['edit_cat'])) {
        echo edit_cat();
    } else {
    ?>
    <h3>View All Categories</h3>
    <div id="add">
        <details>
            <summary>Add Categories</summary>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="cat_name" placeholder="Enter New Category" required="required" />
                <center><button name="add_cat">Add Category</button></center>
            </form>
        </details>
        <table>
            <tr>
                <th>Sr No.</th>
                <th>Categories</th>
                <th>Edit</th> 
                <th>Delete</th>
            </tr>
            <?php echo view_cat(); ?>
        </table>
    </div>
    <?php } ?>
</div>