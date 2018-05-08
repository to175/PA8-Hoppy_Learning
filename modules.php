<?php
session_start();
if(!isset($_SESSION['user'])){
	header('Location: login.php');exit;
}
if($_SESSION['type']==0){
	header('Location: profile.php?msg=Please%20activate%20your%20account&type=info');exit;
}
$listOfModules = '';
$i = 0;
include 'bdd.php';
$req = $pdo->query('SELECT Module_ID, Module_Title FROM hoppy_modules');

while($data = $req->fetch()){
	if($i%2==0){
		$listOfModules .= '<div class="row">';
	}
	
	$listOfModules .= '<div class="col-sm-6">
			    <div class="card">
			      <div class="card-body">
			        <h4 class="card-title">'.$data['Module_Title'].'</h4>
			        <a href="lectures.php?module='.$data['Module_ID'].'" class="btn btn-primary">See lectures</a>
			      </div>
			    </div>
			  </div>';
	
	if($i%2==1){
		$listOfModules .= '</div>
			<br><br>';
	}
	$i++;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ('head.html'); ?>
		<style>
	.icon{
		position: absolute;
    top: 6px;
    right: 10px;
    width: 20px;
    padding-left: 5px;
    line-height: 23px;
		font-size: 20px;
	}
	</style>
		<!-- Title website -->
		<title>Hoppy - Modules</title>
	</head>
	<body>

		<?php include ('nav.php'); ?>

		<div class="container">
			<div class="page-header">
  				<h3>Available Modules</h3>
  				<div class="dropdown-divider"></div>
			</div>
			<?php echo $msg; ?>
			<br>

			<?php echo $listOfModules; ?>
			
		</div><br><br>

		<?php include ('footer.html'); ?>

	</body>
</html>