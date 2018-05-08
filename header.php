<?php
session_start();
?>
<div class="top-right" data-scrollreveal="over 2s">
      <?php
      if(isset($_SESSION['user'])){
        echo '<a href="profile.php" class="btn btn-success btn-md"><i class="fa fa-user" aria-hidden="true"></i> My account</a>';
      }else{
        echo '<a href="login.php" class="btn btn-primary btn-md"><i class="fa fa-sign-in" aria-hidden="true"></i> Log in OR sign up</a>';
      }
      ?>
        
        <!--<a href="">Daltonien</a> 
        <label class="switch">
        <input type="checkbox">
        <span class="slider"></span>
        </label>-->
    </div>
