<?php 
require_once "inc/function.php"; 
include("inc/header.php");
?>
<style type="text/css">
  .card a {
    color: inherit;
    text-decoration: none;
  }
  .course-holder .card {
    width: calc((100% / 3) - 30px);
    margin: 15px;
  }
  .searchform {
    width: 100%;
    max-width: 600px;
    margin: 0px auto;
  }
</style>
<br><br><br><br>
<div class="container">
  <div class="m-3">
    <form class="form searchform" action="search.php" method="get" style="display: flex;">
      <input class="form-control mr-sm-2" type="search" name="s" placeholder="Search" aria-label="Search" value="<?php if (isset($_GET["s"])) { echo $_GET["s"]; } ?>" style="width: 100%;">
      <button class="btn btn-outline-light my-2 my-sm-0" type="submit" style="padding-left: 30px; padding-right: 30px; color: black; border: 1px solid #000;">
        <i class="fa fa-search" aria-hidden="true"></i>
      </button>
    </form>
  </div>
  <div class="course-holder">
    <?php if (!view_search_course()) { ?>
      <style type="text/css">
        body {
          background-image: url("img/search.webp");
          overflow: hidden;
          background-size: auto;
          background-attachment: fixed;
          background-position: right;
          background-repeat: no-repeat;
        }
      </style>
      <div class="error404">
        <h2>Sorry, we couldn't find any results for "<?php echo $_GET["s"]; ?>"</h2><br>
        <h4>Try adjusting your search. Here are some ideas:</h4>
        <p><i class="fa fa-hand-o-right" aria-hidden="true"></i> Make sure all words are spelled correctly.</p>
        <p><i class="fa fa-hand-o-right" aria-hidden="true"></i> Try different search terms.</p>
        <p><i class="fa fa-hand-o-right" aria-hidden="true"></i> Try more general search terms.</p>
        <br><br><br><br><br><br><br><br><br><br><br><br>
      </div>
    <?php } ?>
  </div>
</div>