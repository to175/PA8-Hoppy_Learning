<?php
session_start();
if(isset($_SESSION['user'])&&isset($_GET["deco"])){//connecté et deconnection
    unset($_SESSION["user"]);
		unset($_SESSION["type"]);
		unset($_SESSION['user_data']);
		unset($_SESSION['id']);
		unset($_SESSION['pass']);
    header('Location: login.php?msg=Logout%20ok&type=success');exit;
}

if(isset($_SESSION['user'])){//deja connecté
		header('Location: profile.php');exit;
}

if(isset($_POST["login"])){
    if(isset($_POST["email"])&&isset($_POST["pass"])){
        //connection DB
        include 'bdd.php';
				$conx = $pdo->prepare('SELECT User_ID, User_Type FROM hoppy_users WHERE (User_Email=:email AND User_Password_SHA256=:pass)');
        $conx->execute(array(
            ':email' => $_POST['email'],
            ':pass' => hash('SHA256', $_POST['pass'])
        ));

				if($conx->rowCount()==1){//if user exists
            /*$update = $pdo->prepare('UPDATE hoppy_users SET lastlogin='.time().' WHERE pseudo=:pseudo');
            $update->execute(array(
                ':pseudo' => $_POST['pseudo']
            ));*/
            while($data = $conx->fetch()){
							//0 not activated, 1 activated regular, 2 school, 3 admin
              $_SESSION['type'] = $data['User_Type'];
							$_SESSION['id'] = $data['User_ID'];
            }
            $_SESSION['user'] = $_POST['email'];
						if($_SESSION['type']==0){
							header('Location: profile.php?msg=Please%20activate%20your%20account&type=info');exit;
						}else{
							header('Location: profile.php?msg=Connected&type=success');exit;
						}
        }else{//user do not exists
            header('Location: login.php?msg=User%20not%20found%20with%20thoses%20credentials&type=danger');exit;
        }
    }else{
        header('Location: login.php?msg=Missing%20data%20to%20login&type=danger');exit;
    }
}else if(isset($_POST["signup"])){
	if(isset($_POST["email"])&&isset($_POST["pwd1"])&&isset($_POST["pwd1"])){
		if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
			if($_POST["pwd1"] == $_POST["pwd2"]){
				//connection DB
				include 'bdd.php';
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
				    	$req = $pdo->prepare("INSERT INTO hoppy_users (User_Type, User_Email, User_Password_SHA256, User_SignUpDate, User_LastLogin) 
																		VALUES (0, :email, :pass, NOW(), NOW())");
							$req->execute(array(
									':email' => $_POST['email'],
									':pass' => hash('SHA256', $_POST['pwd1'])
							));

							$req = $pdo->prepare('SELECT User_ID, User_Password_SHA256 FROM hoppy_users WHERE User_Email=:email');
							$req->execute(array(
									':email' => $_POST['email']
							));
							
							while($data = $req->fetch()){
								$_SESSION['id'] = $data['User_ID'];
								$_SESSION['pass'] = $data['User_Password_SHA256'];
							}
							$_SESSION['type'] = 0;
							$_SESSION['user'] = $_POST['email'];
							$_SESSION['sub'] = 0;
							header('Location: profile.php?msg=Please%20wait%2010%20minutes%20while%20your%20activation%20email%20is%20being%20proceed&type=sucess');exit;
				    }else{
							header('Location: login.php?msg=Error%20during%20signup&type=warning&ch2=1');exit;
						}
					}else{//user already exists
							header('Location: login.php?msg=User%20already%20exists&type=danger&ch2=1');exit;
					}
		    }else{//pass do not match
		    	header('Location: login.php?msg=Passwords%20do%20not%20match&type=danger&ch2=1');exit;
		    }
		}else{//incorrect email
			header('Location: login.php?msg=Incorrect%20email&type=danger&ch2=1');exit;
		}
	}else{//missing data
			header('Location: login.php?msg=Missing%20data%20to%20signup&type=danger&ch2=1');exit;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ('head.html'); ?>
		
		<!-- Title website -->
		<title>Hoppy - Login</title>
	</head>
	<body>

		<?php include ('nav.php'); ?>

		<div class="container">
			<div class="row">
			    <div class="col-sm-6">
			      <header class="page-header">
			        <h3>Sign in to Continue</h3>
			        <div class="dropdown-divider"></div>
			      </header>
						<?php if(!isset($_GET['ch2'])){echo $msg;} ?>
			      <form class="form-horizontal" method="post">
			        <div class="form-group">
			          <label class="control-label" for="email">Email</label>
			          <div class="col-sm-10">
			            <input type="email" name="email" class="form-control form-control-sm" id="email" placeholder="Enter email">
			          </div>
			        </div>
			        <div class="form-group">
			          <label class="control-label" for="pwd">Password</label>
			          <div class="col-sm-10"> 
			            <input type="password" name="pass" class="form-control form-control-sm" id="pwd" placeholder="Enter password">
			          	<small id="passwordHelpInline" class="text-muted">
										<a href="login.php?forgot">I forgot my password</a>
									</small>
								</div>
			        </div>
			        <div class="form-group"> 
			          <div class="col-sm-offset-2 col-sm-10">
			            <div class="checkbox">
			              <label><input type="checkbox"> Remember me</label>
			            </div>
			          </div>
			        </div>
			        <div class="form-group"> 
			          <div class="col-sm-offset-2 col-sm-10">
			            <!-- <input type="submit" class="btn btn-default" href ="AccountPage.html" value="Sign In""> -->
			            <button type="submit" name="login" class="btn btn-success btn-sm">Sign In</button>
			          </div>
			        </div>
			      </form>
			    </div>

			    <div class="col-sm-6">
			      <header class="page-header">
			        <h3>Create Account</h3>
			        <div class="dropdown-divider"></div> 
			      </header>
			      <?php if(isset($_GET['ch2'])){echo $msg;} ?>
			      <form class="form-horizontal" action="login.php" method="POST">
			        <div class="form-group">
			          <label class="control-label" for="email">Email</label>
			          <div class="col-sm-10">
			            <input type="email" class="form-control form-control-sm" id="email" placeholder="Enter email" name="email">
			          </div>
			        </div>
			        <div class="form-group">
			          <label class="control-label" for="pwd">Password</label>
			          <div class="col-sm-10"> 
			            <input type="password" class="form-control form-control-sm" id="pwd" placeholder="Enter password" name="pwd1">
						<small id="passwordHelpInline" class="text-muted">
							Must be 8-20 characters long.
						</small>
			          </div>
			        </div>
			        <div class="form-group">
			          <label class="control-label" for="pwd">Confirm password</label>
			          <div class="col-sm-10">
			            <input type="password" class="form-control form-control-sm" id="pwd" placeholder="Confirm password" name="pwd2">
			          </div>
			        </div>
							<div class="form-group"> 
			          <div class="col-sm-offset-2 col-sm-10">
			            <div class="checkbox">
			              <label><input type="checkbox" required> I agree the terms</label>
			            </div>
			          </div>
			        </div>
			        <div class="form-group">
			          <div class="col-sm-offset-2 col-sm-10">
			            <button type="submit" class="btn btn-warning btn-sm" name="signup">Create Account</button>
			          </div>
			        </div>
			      </form>
			    </div>
			  </div>
		</div><br><br>

		<?php include ('footer.html'); ?>
	<!--<script>
	$("html").click(function() {
			if($(".alert-success").length){
				 $(".alert-success").remove();
			}
	});</script>-->
	</body>
</html>