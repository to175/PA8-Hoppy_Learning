<?php
session_start();
date_default_timezone_set('UTC+1');

if(isset($_GET['deleteacc'])){
	include 'bdd.php';
	$conx = $pdo->query("SELECT User_ID FROM hoppy_users");
	while($data = $conx->fetch()){
		if($_GET['deleteacc']==hash('SHA256', "#0Ce1Sel2Est3Plus4Que5Tres6Salé7#".$data['User_ID'])){
			$req = $pdo->prepare("DELETE FROM hoppy_suivi WHERE User_ID=:id");
			$req->execute(array(
					':id' => $data['User_ID']
			));

			$req = $pdo->prepare("DELETE FROM hoppy_users WHERE User_ID=:id");
			$req->execute(array(
					':id' => $data['User_ID']
			));
			if(isset($_SESSION['id'])){
				unset($_SESSION['user']);
				unset($_SESSION['id']);
				unset($_SESSION['type']);
				unset($_SESSION['pass']);
				unset($_SESSION['user_data']);
			}
			header('Location: login.php?msg=Your%20account%20has%20been%20deleted&type=info');exit;
		}
	}
	header('Location: login.php?msg=User%20does%20not%20exist&type=danger');exit;
}

if(isset($_SESSION['id'])&&isset($_POST['deleteacc'])){
	$code = hash('SHA256', "#0Ce1Sel2Est3Plus4Que5Tres6Salé7#".$_SESSION['id']);
	$to  = $_SESSION['user'];
	$subject = 'Hoppy Learning : delete your account !';
	$message = '
	 <html>
		<head>
		 <title>Hoppy Learning : delete your account !</title>
		</head>
		<body>
		 <h1>We are sad to show you this link... See you soon !</1>
		 <p>You can now delete your account by clicking here : <a href="http://theofleury.fr/hoppy/profile.php?deleteacc='.$code.'" style="decoration:underline;color:red;">DELETE ACCOUNT</a>.</p>
		</body>
	 </html>
	 ';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Hoppy Learning <hoppy@efrei.fr>' . "\r\n" . 'Reply-to: Admin <theo175@gmail.com>' . "\r\n";
	if(mail($to, $subject, $message, $headers)){
		unset($_SESSION['user']);
		unset($_SESSION['id']);
		unset($_SESSION['type']);
		unset($_SESSION['pass']);
		unset($_SESSION['user_data']);
		header('Location: login.php?msg=We%20sent%20you%20an%20email%20to%20confirm%20account%20deletion&type=info');exit;
	}
}

if(isset($_GET['activate'])){
	include 'bdd.php';
	$conx = $pdo->query("SELECT User_Email, User_Type FROM hoppy_users");
	
	while($data = $conx->fetch()){
		if($_GET['activate']==hash('SHA256', "0Ce1Sel2Est3Tres4Sale5".$data['User_Email'])){
			if($data['User_Type']==0){//si pas activé
				$schools = array('efrei.net', 'efrei.fr', 'groupe-efrei.net', 'groupe-efrei.fr');
				if(in_array(explode('@', $data['User_Email'])[1], $schools)){
					$type = 2;
				}else{
					$type = 1;
				}

				$conx = $pdo->prepare("UPDATE hoppy_users
															 SET User_Type=:type
															 WHERE User_Email=:email");
				$conx->execute(array(
						':email' => $data['User_Email'],
						':type' => $type
				));

				if(isset($_SESSION['user'])){
					$_SESSION['type'] = $type;
					header('Location: profile.php?msg=Account%20successfuly%20activated&type=success');exit;
				}else{
					header('Location: login.php?msg=Account%20successfuly%20activated&type=success');exit;
				}
			}else{
				//deja activé
				if(isset($_SESSION['user'])){
					header('Location: profile.php?msg=Account%20already%20activated&type=warning');exit;
				}else{
					header('Location: login.php?msg=Account%20alread%20activated&type=warning');exit;
				}
			}
		}
	}	
}

if(!isset($_SESSION['user'])){
	header('Location: login.php');exit;
}else if($_SESSION['type']==0&&!isset($_GET['msg'])){//pas de msg : activation/redirection
	header('Location: profile.php?msg=Please%20activate%20your%20account&type=info');exit;
}else if($_SESSION['type']!=0&&isset($_GET['activate'])){
	header('Location: profile.php?msg=Account%20already%20activated&type=warning');exit;
}

include 'bdd.php';

$conx = $pdo->prepare("SELECT l.Lecture_ID, l.Lecture_Title, l.Lecture_Detail, s.Course_DateBegin, s.Course_DateFinish FROM hoppy_suivi s 
											 INNER JOIN hoppy_users u ON s.User_ID=u.User_ID
											 INNER JOIN hoppy_lectures l ON s.Lecture_ID=l.Lecture_ID
											 WHERE User_Email=:email");
$conx->execute(array(
		':email' => $_SESSION['user']
));
$i = 0;
$listOfLectures = '';
while($data = $conx->fetch()){	
	if($data['Course_DateFinish']=='0000-00-00'){
		if($data['Course_DateBegin']!='0000-00-00'){
			$begin = '<small class="form-text text-muted">Course started on '.date('D \t\h\e jS \o\f M Y', strtotime($data['Course_DateBegin'])).'</small>';//set la date au bon format
		}
		if($i%2==0){
			$listOfLectures .= '<div class="row">';
		}
		
		$listOfLectures .= '
	      <div class="col-sm-6" id="box'.$data['Lecture_ID'].'">
			    <div class="card">
			      <div class="card-body">
			        <h4 class="card-title">'.$data['Lecture_Title'].'</h4>
							<div class="delete" onclick="deleteS('.$data['Lecture_ID'].')"><i class="fa fa-times"></i></div>
			        <p class="card-text">'.$data['Lecture_Detail'].'</p>
			        <a href="courses.php?lecture='.$data['Lecture_ID'].'" class="btn btn-primary">See courses</a>
							'.$begin.'
			      </div>
			    </div>
			  </div>';
		
		if($i%2==1){
			$listOfLectures .= '</div><br><br>';
		}
		$i++;
	}
}

if($i%2 == 1){
	$listOfLectures .= '</div>';
}

if(!isset($_SESSION['user_data'])&&!isset($_GET['deleteacc'])){
	$conx = $pdo->prepare("SELECT * FROM hoppy_users WHERE User_ID=:id");
	$conx->execute(array(
			':id' => $_SESSION['id']
	));
	while($data = $conx->fetch()){
		$firstName = $data['User_FirstName'];
		$lastName = $data['User_LastName'];
		$address = $data['User_Address'];
		$city = $data['User_City'];
		$state = $data['User_State'];
		$zipCode = $data['User_ZipCode'];
		$birthDate = $data['User_BirthDate'];
		$cellPhone = $data['User_CellPhone'];
		$pass = hash('SHA256', $data['User_Password_SHA256']);
	}
	
	$_SESSION['firstName'] = $firstName;
	$_SESSION['lastName'] = $lastName;
	$_SESSION['address'] = $address;
	$_SESSION['city'] = $city;
	$_SESSION['state'] = $state;
	$_SESSION['zipCode'] = $zipCode;
	$_SESSION['birthDate'] = $birthDate;
	$_SESSION['cellPhone'] = $cellPhone;
	$_SESSION['pass'] = $pass;
	$_SESSION['user_data'] = 1;
}else{
	$firstName = $_SESSION['firstName'];
	$lastName = $_SESSION['lastName'];
	$address = $_SESSION['address'];
	$city = $_SESSION['city'];
	$city = $_SESSION['state'];
	$zipCode = $_SESSION['zipCode'];
	$birthDate = $_SESSION['birthDate'];
	$cellPhone = $_SESSION['cellPhone'];
}

if(isset($_POST['editacc'])){//si on edit les infos
	$emailEdit = '';
	$passEdit = '';
	$fullEdit = '';
	
	$modify = false;
	foreach ($_SESSION as $key => $value){
		if (!in_array($key, ['type', 'user', 'id', 'user_data'])){
			if($_POST[$key] != $_SESSION[$key]){
				$modify = true;
			}
		}
	}
	
	if($modify){
		if(
			(strlen($_POST['firstName']) == ''
			|| (strlen($_POST['firstName']) > 2
			&& strlen($_POST['firstName']) < 30))
			&& (strlen($_POST['lastName']) == ''
			|| (strlen($_POST['lastName']) > 2
			&& strlen($_POST['lastName']) < 30))
			&& (strlen($_POST['address']) == ''
			|| (strlen($_POST['address']) > 5
			&& strlen($_POST['address']) < 100))
			&& (strlen($_POST['city']) == ''
			|| (strlen($_POST['city']) > 2
			&& strlen($_POST['city']) < 30))
			&& (strlen($_POST['state']) == ''
			|| (strlen($_POST['state']) > 2
			&& strlen($_POST['state']) < 30))
			&& (strlen($_POST['zipCode']) == ''
			|| strlen($_POST['zipCode']) < 10)
			&& (strlen($_POST['cellPhone']) == ''
			|| (strlen($_POST['cellPhone']) > 5
			&& strlen($_POST['cellPhone']) < 20))
		){
			$conx = $pdo->prepare("UPDATE hoppy_users
													 SET User_FirstName=:firstName, User_LastName=:lastName, User_Address=:address, User_City=:city,
													 		 User_State=:state, User_ZipCode=:zipCode, User_BirthDate=:birthDate, User_CellPhone=:cellPhone
													 WHERE User_ID=:id");
			$conx->execute(array(
				':firstName' => $_POST['firstName'],
				':lastName' => $_POST['lastName'],
				':address' => $_POST['address'],
				':city' => $_POST['city'],
				':state' => $_POST['state'],
				':zipCode' => $_POST['zipCode'],
				':birthDate' => $_POST['birthDate'],
				':cellPhone' => $_POST['cellPhone'],
				':id' => $_SESSION['id']
			));
			unset($_SESSION['user_data']);
			$fullEdit = 'Your%20data%20have%20been%20modified.';
		}else{
			header('Location: profile.php?msg=Check%20the%20form%20please&type=danger');exit;
		}
	}
	
	if($_POST['email'] != $_SESSION['user']){//si on modifie email on renvoie activation
		if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){//vérifier email
			//préparer et envoyer email avec le nouveau token
			$conx = $pdo->prepare('SELECT User_ID FROM hoppy_users WHERE User_Email=:email');
			$conx->execute(array(
					':email' => $_POST['email']
			));

			if($conx->rowCount()==0){//if user do not exist
					$code = hash('SHA256', "0Ce1Sel2Est3Tres4Sale5".$_POST['email']);
					$to  = $_POST['email'];
					$subject = 'Welcome on Hoppy Learning !';
					$message = '
					 <html>
						<head>
						 <title>Welcome on Hoppy Learning !</title>
						</head>
						<body>
						 <h1>Welcome on Hoppy Learning  !</1>
						 <p>You can now activate your account by clicking on <a href="http://theofleury.fr/hoppy/profile.php?activate='.$code.'" style="decoration:underline;">this link</a>.</p>
						</body>
					 </html>
					 ';
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Hoppy Learning <hoppy@efrei.fr>' . "\r\n" . 'Reply-to: Admin <theo175@gmail.com>' . "\r\n";
				
					if(mail($to, $subject, $message, $headers)){
						$conx = $pdo->prepare("UPDATE hoppy_users
													 SET User_Type=0, User_Email=:email
													 WHERE User_ID=:id");
						$conx->execute(array(
							':email' => $_POST['email'],
							':id' => $_SESSION['id']
						));

						$_SESSION['type'] = 0;
						$_SESSION['user'] = $_POST['email'];
						$emailEdit = '%20Please%20activate%20your%20account.';
					}else{
						header('Location: profile.php?msg=Error%20during%20emailing&type=warning');exit;
					}
				}else{//user already exists
						header('Location: profile.php?msg=User%20already%20exists&type=danger');exit;
				}
		}else{
			header('Location: profile.php?msg=%20already%20exists&type=danger');exit;
		}
	}
	
	//si le user a inscrit un password différent
	if($_POST['pwd'] != '' && $_SESSION['pass'] != hash('SHA256', $_POST['pwd'])){
		$conx = $pdo->prepare("UPDATE hoppy_users
													 SET User_Password_SHA256=:pass
													 WHERE User_ID=:id");
		$conx->execute(array(
				':pass' => hash('SHA256', $_POST['pwd']),
				':id' => $_SESSION['id']
		));
		//nouveau pass dans session
		$_SESSION['pass'] =  hash('SHA256', $_POST['pwd']);
		$passEdit = '%20Your%20password%20has%20been%20modified.';
	}
	header('Location: profile.php?msg='.$fullEdit.$emailEdit.$passEdit.'&type=success');exit;
}

if($_SESSION['type'] == 5){
		//ADMIN
	$navAdmin = '<a class="nav-link" id="v-pills-admin-tab" data-toggle="pill" href="#v-pills-admin" role="tab" aria-controls="v-pills-admin" aria-selected="false"><i class="fa fa-cogs" aria-hidden="true"></i> Admin</a>';
	$admin = '
	<div class="card border-secondary mb-3">
		<div class="card-header">Add a new school</div>
		<div class="card-body text-center">
			<form class="form-inline" method="POST" style="width:100%;margin:auto;">
			
				<label class="sr-only" for="inlineFormInput">Name</label>
				<input type="text" class="form-control mr-sm-2" id="inlineFormInput" placeholder="Name">

				<div class="input-group input-group-md mr-sm-2">
					<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroup-sizing-md">Http://</span>
					</div>
					<input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="Domain">
				</div>
				
				<button type="submit" class="btn btn-primary">Create</button>
			</form>
		</div>
	</div>
	
	<div class="card border-secondary mb-3">
		<div class="card-header">Toggle admin</div>
		<div class="card-body text-center">
			<form class="form-inline" method="POST" style="width:100%;margin:auto;">
			
				<label class="sr-only" for="inlineFormInput">Email</label>
				<input type="text" class="form-control mr-sm-2" id="inlineFormInput" placeholder="Email">
				
				<button type="submit" class="btn btn-primary">Toggle</button>
			</form>
		</div>
	</div>
	
	<div class="card border-secondary mb-3">
		<div class="card-header">Add a new lecture</div>
		<div class="card-body text-center">
			<form class="form-inline mb-4" method="POST" style="width:100%;margin:auto;">
				<label class="sr-only" for="inlineFormInput">Title</label>
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Title">

				<label class="sr-only" for="inlineFormInputGroup">Detail</label>
				<div class="input-group mb-2 mr-sm-2 mb-sm-0">
					<input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Detail">
				</div>

				<button type="submit" class="btn btn-primary">Create</button><br>
			</form>
			<a href="lectures.php?msg=Please%20select%20a%20lecture&type=info">Add a COURSE or add a QUIZ.</a>
		</div>
	</div>
	';
}else{
	$admin = '';
}

$scoreTotal = 0;
$nbOfQuiz = 0;
$tableOfScore = '<table class="table table-striped table-hover">
									<thead class="thead-light">
										<tr>
											<th scope="col">Course title</th>
											<th scope="col">Date of quiz</th>
											<th scope="col">Score</th>
										</tr>
									</thead>';

//SELECT all scores
$conx = $pdo->prepare("SELECT Score_Points, Score_DateSubmition, Course_ID
												FROM hoppy_scores
												WHERE User_ID=:id");
$conx->execute(array(
		':id' => $_SESSION['id']
));
while($data = $conx->fetch()){
	$conx2 = $pdo->prepare("SELECT Course_Title
												FROM hoppy_courses
												WHERE Course_ID=:id");
	$conx2->execute(array(
			':id' => $data['Course_ID']
	));
	$nbOfQuiz++;
	while($data2 = $conx2->fetch()){
			//on ajoute au tableau avec le score qu'on ajoute au total
			$tableOfScore.='<tr>
										<th scope="row">'.$data2['Course_Title'].'</th>
										<td>'.date('D \t\h\e jS \o\f M Y', strtotime($data['Score_DateSubmition'])).'</td>
										<td>'.$data['Score_Points'].'</td>
									</tr>';
			$scoreTotal += $data['Score_Points'];
	}
}

//requete sur tous les scores, addition, tri par scores et affichage du rang
$conx = $pdo->query("SELECT * FROM hoppy_scores");
$scores = array();
while($data = $conx->fetch()){
	//si user existe pas on set à 0 son score sinon on ajoute
	if(!isset($scores[$data['User_ID']])){
		$scores[$data['User_ID']] = 0;
	}else{
		$scores[$data['User_ID']] += $data['Score_Points'];
	}
}

asort($scores);

$rank = 0;
foreach($scores as $key => $value){
	if($key == $_SESSION['id']){
		$rank++;
		break;
	}
}

$conx = $pdo->query("SELECT * FROM hoppy_quiz");
$nbOfQuizTotal = $conx->rowCount();
$avancement = abs(($nbOfQuiz/$nbOfQuizTotal)*100);

$tableOfScore.='<tr>
									<td colspan="2">Total</td>
									<td>'.$scoreTotal.' points</td>
								</tr>
								<tr>
									<td colspan="2">Classement</td>
									<td>#'.$rank.'</td>
								</tr>
								<tr>
									<td>Avancement</td>
									<td colspan="2">
										<div class="progress" style="border: 1px black solid;">
											<div class="progress-bar" role="progressbar" style="width: '.$avancement.'%;" aria-valuenow="'.$nbOfQuiz.'" aria-valuemin="0" aria-valuemax="'.$nbOfQuizTotal.'">'.$avancement.'%</div>
										</div>
									</td>
								</tr>
							</tbody></table>';

if($nbOfQuiz==0){
	$tableOfScore = '<div class="alert alert-info">
											<strong><i class="fa fa-info" aria-hidden="true"></i></strong> You do not have taken any quiz...
										</div>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include ('head.html'); ?>
	<!-- Title website -->
	<title>Hoppy - My account</title>
</head>

<body>

	<?php include ('nav.php'); ?>

	<div class="container">

		<div class="row">
			<div class="col-sm-2">
				<br><br><br>
				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="v-pills-dashboard-tab" data-toggle="pill" href="#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="true"><i class="fa fa-address-card" aria-hidden="true"></i> Profile</a>
					<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fa fa-user" aria-hidden="true"></i> Infos</a>
					<a class="nav-link" id="v-pills-subscribe-tab" data-toggle="pill" href="#v-pills-subscribe" role="tab" aria-controls="v-pills-subscribe" aria-selected="false"><i class="fa fa-trophy" aria-hidden="true"></i> Plans</a>
					<?php echo $navAdmin; ?>
				</div>
			</div>

			<div class="col-sm-10">
				<div class="card">
					<div class="card-body text-center">
						<?php if(isset($_SESSION['firstName'])&&isset($_SESSION['lastName'])&&$_SESSION['firstName']!=''&&$_SESSION['firstName']!=''){
										echo ucwords($_SESSION['firstName']." ".$_SESSION['lastName'])."'s account";
									}else if(isset($_SESSION['user'])){
										echo ucfirst(explode('@', $_SESSION['user'])[0])."'s account";
									} ?>
					</div>
				</div>
				<br>
				<?php echo $msg; ?>
				<br>
				<div class="tab-content" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
						<?php echo $tableOfScore; ?>
						<br>
						<?php if($listOfLectures != ''){
										echo $listOfLectures;
									}else if($_SESSION['type']!=0){
										echo '<div class="alert alert-info">
														<strong><i class="fa fa-info" aria-hidden="true"></i></strong> You do not have started any lecture...
													</div>';
									} ?>
					</div>
					<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
						<p>You can change your data anytime.</p>
						<div class="col-sm col-5">
							<form class="form-horizontal" method="POST">
								<div class="form-group">
									<!-- First Name & Last Name -->
									<div class="row">
										<div class="col">
											<label for="exampleInputPassword1">First Name</label>
											<input type="text" class="form-control form-control-sm" id="firstName" name="firstName" value="<?php echo $firstName; ?>">
										</div>
										<div class="col">
											<label for="exampleInputPassword1">Last Name</label>
											<input type="text" class="form-control form-control-sm" id="lastName" name="lastName" value="<?php echo $lastName; ?>">
										</div>
									</div>
								</div>
								<!-- Adress -->
								<div class="form-group">
									<label for="inputAddress">Address</label>
									<input type="text" class="form-control form-control-sm" id="inputAddress" name="address" value="<?php echo $address; ?>">
								</div>
								<div class="form-group">
									<!-- City, State & Zip Code -->
									<div class="row">
										<div class="col-7">
											<label for="inputCity">City</label>
											<input type="text" class="form-control form-control-sm" id="inputCity" name="city" value="<?php echo $city; ?>">
										</div>
										<div class="col">
											<label for="inputState">State</label>
											<select id="inputState" class="form-control form-control-sm" name="state" value="<?php echo $state; ?>">
									      <option selected>Choose...</option>
									      <option>France</option>
									    </select>
										</div>
										<div class="col">
											<label for="inputZip">Zip Code</label>
											<input type="text" class="form-control form-control-sm" id="inputZip" name="zipCode" value="<?php echo $zipCode; ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!-- Date of Birth & Cellphone number -->
									<div class="row">
										<div class="col-7">
											<label for="inputCity">Date of Birth</label>
											<input type="date" id="birthDate" class="form-control form-control-sm" name="birthDate" value="<?php echo $birthDate; ?>">
										</div>
										<div class="col">
											<label for="inputState">Cellphone Number</label>
											<input type="text" class="form-control form-control-sm" id="cellNumber" name="cellPhone" value="<?php echo $cellPhone; ?>">
										</div>
									</div>
								</div>

								<!-- Email & Password for account -->
								<div class="form-group mb-3">
									<label for="exampleInputEmail1">Email address</label>
									<input type="email" class="form-control form-control-sm" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="<?php echo $_SESSION['user']; ?>">
									<small id="emailHelp" class="form-text text-muted">Your e-mail will be the id account</small>
								</div>
								<div class="card" style="border-color:red;">
									<div class="card-body text-center">
										<div class="form-group mb-0">
											<label for="exampleInputPassword1">Change password ?</label>
											<input type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="New password" name="pwd">
											<small id="passwordHelpInline" class="text-muted">
												Must be 8-20 characters long.
											</small>
										</div>
									</div>
								</div>
								<br>
								<button type="submit" class="btn btn-primary btn-md" name="editacc">Submit</button>
							</form>
							<br><br>
							<div class="card" style="border-color:red;">
								<div class="card-body text-center" style="color:red;">
									Danger zone
									<form method="POST" action="profile.php">
										<div class="form-check pl-0">
											<br>
											<button type="submit" class="btn btn-danger btn-sm" name="deleteacc">Delete account</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="v-pills-subscribe" role="tabpanel" aria-labelledby="v-pills-subscribe-tab">
						<div class="alert alert-info">
							<strong><i class="fa fa-info" aria-hidden="true"></i></strong> Actually you do not have any subscription
						</div>
						<p>Here are our rates to have access to all functionalities. By subscribing to the monthly rate you have the possibility (for schools) to add the list of students to the STUDENTS step. The price will be calculated according to the number of students and a discount of 10% and available for all establishments.</p>
						<div class="card-deck">
							<div class="card border-secondary mb-3" style="max-width: 20rem;">
								<div class="card-title text-center mb-0">
									<br>
									<p><b style="font-size:20px;">Free</b></p>
								</div>
								<div class="dropdown-divider"></div>
								<!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
								<div class="card-body">
									<h4 class="card-title text-center">Life-time</h4>
									<small></small>
								</div>
								<div class="card-footer">
									<button class="btn btn-outline-secondary">FREE</button>
								</div>
							</div>
							<div class="card border-warning mb-3" style="max-width: 20rem;">
								<div class="card-title text-center mb-0">
									<small>Best-seller</small>
									<br>
									<p class="mb-0"><b style="font-size:30px;"><i class="fa fa-euro" aria-hidden="true"></i> 9<span style="font-size:10px;">99</span></b></p>
								</div>
								<div class="dropdown-divider"></div>
								<!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
								<div class="card-body">
									<h4 class="card-title text-center">One month of subscription</h4>
								</div>
								<div class="card-footer">
									<form action="https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2MJWNVRDPPY28" method="post">
										<button class="btn btn-outline-primary">BUY NOW</button>
									</form>
								</div>
							</div>
							<div class="card border-secondary mb-3" style="max-width: 20rem;">
								<div class="card-title text-center mb-0">
									<br>
									<p class="mb-0"><b style="font-size:30px;"><i class="fa fa-euro" aria-hidden="true"></i> 39<span style="font-size:10px;">99</span></b></p>
								</div>
								<div class="dropdown-divider"></div>
								<!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
								<div class="card-body">
									<h4 class="card-title">Six months of subscription</h4>
								</div>
								<div class="card-footer">
									<form action="https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S924MMNASQT6Q" method="post">
										<button class="btn btn-outline-primary">BUY NOW</button>
									</form>
								</div>
							</div>
							<div class="card border-secondary mb-3" style="max-width: 20rem;">
								<div class="card-title text-center mb-0">
									<br>
									<p class="mb-0"><b style="font-size:30px;"><i class="fa fa-euro" aria-hidden="true"></i> 69<span style="font-size:10px;">99</span></b></p>
								</div>
								<div class="dropdown-divider"></div>
								<!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
								<div class="card-body">
									<h4 class="card-title">One year of subscription</h4>
									<small style="color:red;">Free VR headset sent at your home</small>
								</div>
								<div class="card-footer">
									<form action="https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KPTVKJRAK85TQ" method="post">
										<button class="btn btn-outline-primary">BUY NOW</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-admin" role="tabpanel" aria-labelledby="v-pills-admin-tab">
						<?php echo $admin; ?>
					</div>
					
				</div>
			</div>
		</div>
	</div><br><br>

	<?php include ('footer.html'); ?>

	<script>
	
		$("html").click(function() {
			if($(".alert-success").length){
				 $(".alert-success").remove();
			}
	});
		
		function deleteS(id){
			$.ajax({
					type: "POST",
					url: "ajax.php",
					data: {
						deleteS: id
					},
					success: function(rep) {}
			});
			$( "#box"+id ).remove();
		}
		//suppprimer en ajax le suivi
		//supprimer la box en jquery
	</script>
</body>

</html>