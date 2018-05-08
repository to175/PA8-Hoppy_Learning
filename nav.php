<?php
$page = explode('.', explode('hoppy/', $_SERVER['PHP_SELF'])[1])[0];
$index = '';
$profile = '';
$lectures = '';
if($page == ' index'){
  $index = 'active';
}else if($page == 'profile'){
  $profile = ' active';
}else if($page == 'modules' || $page == 'lectures' || $page == 'courses'){
  $modules = ' active';
}
$navLinks = '
    <li class="nav-item'.$index.'">
        <a class="nav-link" href="index.php"><i class="fa fa-home fa-fw" aria-hidden="true"></i> Home</a>
      </li>';
if(isset($_SESSION['user'])){
    $navLinks .= '
    <li class="nav-item'.$profile.'">
        <a class="nav-link" href="profile.php"><i class="fa fa-user" aria-hidden="true"></i> My Account</a>
      </li>
      <li class="nav-item'.$modules.'">
        <a class="nav-link" href="modules.php"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Modules</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="forum" target="_blank"><i class="fa fa-comments" aria-hidden="true"></i> Forum</a>
      </li>
    ';
}
$navLinks .= '<li class="nav-item">
        <a class="nav-link" href="index.php#about"><i class="fa fa-question" aria-hidden="true"></i> About us</a>
      </li>';
?>

<div style="background-color:#fff!important;background-image:url('images/bg.png');" class="jumbotron">
  <div class="container text-center">
    <h1 class="display-3"><img src="images/lightbubble.png" style="height:4.5rem;position: absolute; margin-left: -90px;"> Hoppy Learning</h1>
  </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php echo $navLinks; ?>
    </ul>
    <?php 
      if(!isset($_SESSION['user'])){
        echo '<ul class="navbar-nav my-2 my-lg-0">
                <li class="nav-item active">
                  <a class="nav-link" href="login.php"><i class="fa fa-user-circle" aria-hidden="true"></i> Register</a>
                </li>
              </ul>';        
      }else{
        echo '<ul class="navbar-nav my-2 my-lg-0">
                <li class="nav-item active">
                  <a class="nav-link" href="login.php?deco=1"><i class="fa fa-sign-out" aria-hidden="true"></i> Log out</a>
                </li>
              </ul>'; 
      }
    ?>
  </div>
</nav>
<script>
 /*function removeParam(parameter)
{
  var url=document.location.href;
  var urlparts= url.split('?');

 if (urlparts.length>=2)
 {
  window.history.pushState('',document.title,urlparts.shift()); // added this line to push the new url directly to url bar .
}
return url;
}
  removeParam('lecture');*/
</script>

<?php 
if(isset($_GET["msg"])&&isset($_GET["type"])){
  switch($_GET["type"]){
    case 'success':
      $class = 'success';
      $icon = '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
      break;
     case 'info':
      $class = 'info';
      $icon = '<i class="fa fa-info" aria-hidden="true"></i>';
      break;
     case 'warning':
      $class = 'warning';
      $icon = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
      break;
     case 'danger':
      $class = 'danger';
      $icon = '<i class="fa fa-bug" aria-hidden="true"></i>';
      break;
     default:
      $class = 'info';
      $icon = '<i class="fa fa-info" aria-hidden="true"></i>';
  }
  $msg = '
  <div class="alert alert-'.$class.'">
    <strong>'.$icon.'</strong> '.$_GET["msg"].'
  </div>
  ';
}else{
  $msg = '';
}
?>
