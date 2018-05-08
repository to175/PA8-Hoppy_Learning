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
if(!isset($_GET['lecture'])){
	header('Location: lectures.php?msg=Please%20select%20a%20correct%20lecture&type=danger');exit;
}
$listOfCourses = '';

include 'bdd.php';
$conx = $pdo->prepare('SELECT Module_Title FROM hoppy_modules WHERE Module_ID=:module');
$conx->execute(array(
		':module' => $_GET['module']
));
while($data = $conx->fetch()){
	$module_Title = $data['Module_Title'];
}

$conx = $pdo->prepare('SELECT Lecture_Title FROM hoppy_lectures WHERE Lecture_ID=:lecture');
$conx->execute(array(
		':lecture' => $_GET['lecture']
));
while($data = $conx->fetch()){
	$lecture_Title = $data['Lecture_Title'];
}

$conx = $pdo->prepare('SELECT Course_ID, Course_Title, Course_Detail, Course_URL FROM hoppy_courses WHERE Lecture_ID=:lecture');
$conx->execute(array(
		':lecture' => $_GET['lecture']
));
$listOfVideos = '';
$listOfVideos2 = '';
while($data = $conx->fetch()){
	if($_SESSION['type']==5){
		$icon = '<div class="delete" onclick="alert(\'delete\')"><i class="fa fa-times"></i></div>';
	}
	$listOfCourses .= '<div class="row">
			  <div class="col-sm-6">
			    <div class="card">
			      <div class="card-body" style="height:330px;">
			        <p class="card-text" style="margin:0;">
							<div id="player'.$data['Course_ID'].'"></div>
							</p>
			      </div>
			    </div>
			  </div>
			  <div class="col-sm-6">
			    <div class="card">
			      <div class="card-body">
			        <h4 class="card-title">'.$data['Course_Title'].'</h4>
							'.$icon.'
			        <p class="card-text">'.$data['Course_Detail'].'</p>
			        <a href="quiz.php?module='.$_GET['module'].'&lecture='.$_GET['lecture'].'&course='.$data['Course_ID'].'" class="btn btn-primary"><i class="fa fa-question-circle" aria-hidden="true"></i> Go to quiz</a>
			      </div>
			    </div>
			  </div>
			</div>
			<br>';
	
			$listOfVideos .= 'var player'.$data['Course_ID'].';';
	
			$listOfVideos2 .= "player".$data['Course_ID']." = new YT.Player('player".$data['Course_ID']."', {
            height: '100%',
            width: '100%',
            videoId: '".$data['Course_URL']."',
						playerVars: { 'rel': 0, 'showinfo': 0 },
            events: {
                onReady: onPlayerReady,
                onStateChange: onPlayerStateChange
            }
        });";
}

if(isset($_GET['finish'])){
	try{
		$conx = $pdo->prepare("UPDATE hoppy_suivi
													 SET Course_DateFinish=NOW()
													 WHERE Lecture_ID=:lecture AND User_ID=:id");
		$conx->execute(array(
			':lecture' => $_GET['lecture'],
			':id' => $_SESSION['id']
		));
		header('Location: lectures.php?msg=Lecture%20marked%20as%20finished%20CONGRATS&type=success');exit;
	}catch(Exception $e){
		header('Location: lectures.php?msg=Course%20never%20started&type=danger');exit;
	}
}

if($_SESSION['type'] == 5){
		//ADMIN
	$admin = '
	<div class="card border-primary mb-3">
	<div class="card-header">Add a new course</div>
		<div class="card-body text-center">
			<form class="form-inline method="POST" style="width:100%;margin:auto;">
				<label class="sr-only" for="inlineFormInput">Title</label>
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Title">

				<label class="sr-only" for="inlineFormInput">Detail</label>
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Detail">

				<label class="sr-only" for="inlineFormInputGroup">Url</label>
				<div class="input-group mb-2 mr-sm-2 mb-sm-0">
					<input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Url">
				</div>
				
				<button type="submit" class="btn btn-primary">Create</button>
			</form>
		</div>
	</div>
	';
}else{
	$admin = '';
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ('head.html'); ?>
		<link rel="stylesheet" href="css/quiz.css">
		<script src="https://www.youtube.com/player_api" type="text/javascript"></script>
		<!-- Title website -->
		<title>Hoppy - Courses</title>
	</head>
	<body>
		
		<?php include ('nav.php'); ?>

		<div class="container">
			<div class="page-header">
  				<h3>
						<a style="color:#636b6f!important;" href="modules.php">Modules</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
						<a style="color:#636b6f!important;" href="lectures.php?module=<?php echo $_GET['module']; ?>"><?php echo $module_Title; ?></a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
						<?php echo $lecture_Title; ?></h3>
  				<div class="dropdown-divider"></div>
			</div><br>
			<div class="alert alert-info">
				<strong><i class="fa fa-info" aria-hidden="true"></i></strong> Subscribe a plan to have access to all courses !
			</div>
			<?php echo $msg; ?>
			<?php echo $admin; ?>
			<br>
			<?php echo $listOfCourses; ?>
			<div class="card">
					<div class="card-body text-center">
						<a href="courses.php?lecture=<?php echo $_GET['lecture']; ?>&finish" class="btn btn-success">I have finished this course</a>
				</div>
			</div>
		</div><br><br>

		<?php include ('footer.html'); ?>
		<script>
			<?php echo $listOfVideos; ?>
    function onYouTubePlayerAPIReady() {
        <?php echo $listOfVideos2; ?>
    }
    // autoplay video
    function onPlayerReady(event) {
        console.log('here','ready');
        // autoplay video
				// event.target.playVideo();
    }
		</script>
		
		<script suivi="1">
    // when video ends
    function onPlayerStateChange(event) {
        console.log('lsklds',event);
        if(event.data === 1) {
            $.ajax({
								type: "POST",
								url: "ajax.php",
								data: {
									lecture: <?php echo $_GET['lecture']; ?>
								},
								success: function(rep) {
									$('script[suivi="1"]').html("function onPlayerStateChange(event) {console.log('lsklds',event);}");
								}
						});
        }
    }
		</script>
	</body>
</html>