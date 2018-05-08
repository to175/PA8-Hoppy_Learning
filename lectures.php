<?php
session_start();
if(!isset($_SESSION['user'])){
	header('Location: login.php');exit;
}
if($_SESSION['type']==0){
	header('Location: profile.php?msg=Please%20activate%20your%20account&type=info');exit;
}
if(!isset($_GET['module'])){
	header('Location: modules.php?msg=Please%20select%20a%20correct%20module&type=danger');exit;
}
$listOfLectures = '';
$i = 0;
include 'bdd.php';
$conx = $pdo->prepare('SELECT Module_Title FROM hoppy_modules WHERE Module_ID=:module');
$conx->execute(array(
		':module' => $_GET['module']
));
while($data = $conx->fetch()){
	$module_Title = $data['Module_Title'];
}

$req = $pdo->prepare('SELECT Lecture_ID, Course_DateFinish FROM hoppy_suivi WHERE User_ID=:id');
$req->execute(array(
	':id' => $_SESSION['id']
));
$lectures = array();
$dates = array();
while($data = $req->fetch()){
	array_push($lectures, $data['Lecture_ID']);
	array_push($dates, $data['Course_DateFinish']);	
}

$req = $pdo->prepare('SELECT Lecture_ID, Lecture_Title, Lecture_Detail FROM hoppy_lectures WHERE Module_ID=:module');
$req->execute(array(
	':module' => $_GET['module']
));
		
while($data = $req->fetch()){
	if($_SESSION['type']!=5){
		if(in_array($data['Lecture_ID'], $lectures)){
			if($dates[array_search($data['Lecture_ID'], $lectures)] != '0000-00-00'){
				$icon = '<div class="icon"><i class="fa fa-check-square-o"></i></div>';
			}else{
				$icon = '<div class="icon"><i class="fa fa-bookmark"></i></div>';
			}
		}else{
			$icon = '';
		}
	}else{
		$icon = '<div class="delete" onclick="alert(\'delete\')"><i class="fa fa-times"></i></div>';
	}
	
	
	if($i%2==0){
		$listOfLectures .= '<div class="row">';
	}

	$listOfLectures .= '<div class="col-sm-6">
			    <div class="card">
			      <div class="card-body">
			        <h4 class="card-title">'.$data['Lecture_Title'].'</h4>
							'.$icon.'
			        <p class="card-text">'.$data['Lecture_Detail'].'</p>
			        <a href="courses.php?module='.$_GET['module'].'&lecture='.$data['Lecture_ID'].'" class="btn btn-primary" onclick="ajoutSuivi('.$data['Lecture_ID'].')">See courses</a>
			      </div>
			    </div>
			  </div>';
	
	if($i%2==1){
		$listOfLectures .= '</div>
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
		<title>Hoppy - My lectures</title>
	</head>
	<body>

		<?php include ('nav.php'); ?>

		<div class="container">
			<div class="page-header">
  				<h3>
						<a style="color:#636b6f!important;" href="modules.php">Modules</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
						<?php echo $module_Title; ?></h3>
  				<div class="dropdown-divider"></div>
			</div>
			<div class="alert alert-info">
				<strong><i class="fa fa-info" aria-hidden="true"></i></strong> Subscribe a plan to have access to all lectures !
			</div>
			<?php echo $msg; ?>			
			<br>

			<?php if(strlen($listOfLectures)==0){
							echo '<div class="alert alert-info">
											You have not started any lecture...
										</div>';
						}else{ echo $listOfLectures; } ?>
			
		</div><br><br>

		<?php include ('footer.html'); ?>

	</body>
</html>