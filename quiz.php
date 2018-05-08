<?php
session_start();
if(!isset($_SESSION['user'])){
	header('Location: login.php');exit;
}

if($_SESSION['type']==0){
	header('Location: profile.php?msg=Please%20activate%20your%20account&type=info');exit;
}
if(!isset($_GET['module']) || !isset($_GET['lecture']) || !isset($_GET['course'])){
	header('Location: courses.php?lecture='.$_GET['lecture'].'&msg=Please%20select%20a%20correct%20module%20or%20lecture%20and%20course&type=danger');exit;
}

include 'bdd.php';

if(isset($_POST['checkScore'])){
	
}

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

$conx = $pdo->prepare('SELECT Course_Title, Course_URL FROM hoppy_courses WHERE Course_ID=:id');
$conx->execute(array(
		':id' => $_GET['course']
));
while($data = $conx->fetch()){
  $course_Title = $data['Course_Title'];
  $url = $data['Course_URL'];
}

$nbOfQuiz = 0;
$listOfQuiz = '<form action="quiz.php?module='.$_GET['module'].'&lecture='.$_GET['lecture'].'&course='.$_GET['course'].'" method="post">
									<div class="card border-primary mb-6" style="max-width: none;margin:auto;">
									<div class="card-header pt-4 pl-4"><h4><i class="fa fa-question-circle" aria-hidden="true"></i> Quiz of the course</h4></div>
									<div class="card-body text-primary">';

$req = $pdo->prepare('SELECT Quiz_ID, Quiz_Question, Quiz_Ans1, Quiz_Ans2, Quiz_Ans3, Quiz_Ans4 FROM hoppy_quiz WHERE Course_ID=:course');
$req->execute(array(
		':course' => $_GET['course']
));
$count1 = $req->rowCount();

$req0 = $pdo->prepare('SELECT Score_Points, Score_ID, Score_Points FROM hoppy_scores WHERE Course_ID=:course AND User_ID=:id');
$req0->execute(array(
		':course' => $_GET['course'],
		':id' => $_SESSION['id']
));
$count2 = $req0->rowCount();

if($count1 != $count2){
	while($data = $req->fetch()){
		$nbOfQuiz++;
		if($_SESSION['type']==5){
			$icon = '<i class="fa fa-times" onclick="alert(\'delete\')"></i>';
		}
		$listOfQuiz .= '
						<h5 class="card-title">'.$icon.' '.$data['Quiz_Question'].'</h5>
						<p class="card-text">
							<div class="col-md-12">
									<div class="funkyradio">
											<div class="funkyradio-primary">
													<input type="checkbox" namecheckboxradio" id="checkbox'.$data['Quiz_ID'].'_1"/>
													<label for="checkbox'.$data['Quiz_ID'].'_1">'.$data['Quiz_Ans1'].'</label>
											</div>
									</div>
							</div>
							<div class="col-md-12">
									<div class="funkyradio">
											<div class="funkyradio-primary">
													<input type="checkbox" name="checkbox" id="checkbox'.$data['Quiz_ID'].'_2"/>
													<label for="checkbox'.$data['Quiz_ID'].'_2">'.$data['Quiz_Ans2'].'</label>
											</div>
									</div>
							</div>
							<div class="col-md-12">
									<div class="funkyradio">
											<div class="funkyradio-primary">
													<input type="checkbox" name="checkbox" id="checkbox'.$data['Quiz_ID'].'_3"/>
													<label for="checkbox'.$data['Quiz_ID'].'_3">'.$data['Quiz_Ans3'].'</label>
											</div>
									</div>
							</div>
							<div class="col-md-12">
									<div class="funkyradio">
											<div class="funkyradio-primary">
													<input type="checkbox" name="checkbox" id="checkbox'.$data['Quiz_ID'].'_4"/>
													<label for="checkbox'.$data['Quiz_ID'].'_4">'.$data['Quiz_Ans4'].'</label>
											</div>
									</div>
							</div><br><br>';
	}
	if($nbOfQuiz==0){
		$listOfQuiz .= '<h5>No quiz available...</h5>';
	}else{
		$listOfQuiz .= '<br><br><button type="submit" class="btn btn-primary" name="checkScore"><i class="fa fa-trophy" aria-hidden="true"></i> Get my score</button></form>';
	}
}else{
	$listOfQuiz .= '<h5>Quiz already sent ! Your score is : '.$req0->fetch()['Score_Points'].'</h5>';
	//check score et afficher
}
$listOfQuiz .= '</p></div></div><br>';

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
	<div class="card-header">Add a new question</div>
		<div class="card-body text-center">
			<form class="form-group" method="POST" style="width:100%;margin:auto;">
      
        <div class="form-group row">
          <div class="col-md col-form-label">
				    <label class="sr-only" for="inlineFormInput">Question</label>
				    <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Question">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md col-form-label">
              <div class="funkyradio">
                  <div class="funkyradio-primary">
                      <input type="checkbox" name="checkbox" id="checkbox1"/>
                      <label for="checkbox1">
                        <input type="text" class="form-control check-input" aria-label="Text input with checkbox" placeholder="Answer b">
                      </label>
                  </div>
              </div>
          </div>
          <div class="col-md col-form-label">
              <div class="funkyradio">
                  <div class="funkyradio-primary">
                      <input type="checkbox" name="checkbox" id="checkbox2"/>
                      <label for="checkbox2"><input type="text" class="form-control check-input" aria-label="Text input with checkbox" placeholder="Answer b"></label>
                  </div>
              </div>
          </div>
        </div>
        
        <div class="form-group row">
          <div class="col-md col-form-label">
              <div class="funkyradio">
                  <div class="funkyradio-primary">
                      <input type="checkbox" name="checkbox" id="checkbox3"/>
                      <label for="checkbox3"><input type="text" class="form-control check-input" aria-label="Text input with checkbox" placeholder="Answer b"></label>
                  </div>
              </div>
          </div>
          <div class="col-md col-form-label">
              <div class="funkyradio">
                  <div class="funkyradio-primary">
                      <input type="checkbox" name="checkbox" id="checkbox4"/>
                      <label for="checkbox4"><input type="text" class="form-control check-input" aria-label="Text input with checkbox" placeholder="Answer b"></label>
                  </div>
              </div>
          </div>
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
    <style>
      .form-group{
        margin-bottom:0;
      }
      .check-input{
        width:100%;
        padding-left:50px;
      }
    </style>
		<!-- Title website -->
		<title>Hoppy - Quiz</title>
	</head>
	<body>

		<?php include ('nav.php'); ?>

		<div class="container">
			<div class="page-header">
        <h3><a style="color:#636b6f!important;" href="modules.php">Modules</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
					<a style="color:#636b6f!important;" href="lectures.php?module=<?php echo $_GET['module']; ?>"><?php echo $module_Title; ?></a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
					<a style="color:#636b6f!important;" href="courses.php?module=<?php echo $_GET['module']; ?>&lecture=<?php echo $_GET['lecture']; ?>"><?php echo $lecture_Title; ?></a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
					<?php echo $course_Title.' (Quiz)'; ?></h3>
  				<div class="dropdown-divider"></div>
			</div><br>
			<?php echo $admin; ?>
			<br>
			<div class="row">
				<div class="col-sm-6" style="margin:auto;">
					<div class="card">
						<div class="card-body" style="height:380px;">
						<h4 class="card-title"><?php echo $course_Title; ?></h4>
							<p class="card-text" style="margin:0;">
								<iframe width="100%" height="300" src="https://www.youtube.com/embed/'.$url.'?rel=0&showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
							</p>
						</div>
					</div>
				</div>
			</div><br>
			<?php echo $listOfQuiz; ?>
			<div class="card">
					<div class="card-body text-center">
						<nav aria-label="...">
              <ul class="pagination justify-content-center" style="margin-bottom:0;">
                <li class="page-item disabled">
                  <span class="page-link" href="#">Previous quiz</span>
                </li>
                <li class="page-item disabled">
                  <a class="page-link" href="#">Next quiz</a>
                </li>
              </ul>
            </nav>
				</div>
			</div>
		</div><br><br>

		<?php include ('footer.html'); ?>
	</body>
</html>