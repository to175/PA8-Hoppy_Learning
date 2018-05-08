<?php

if(isset($_POST["signuponlogin"])){
	if(isset($_POST["email"])&&isset($_POST["pwd1"])&&isset($_POST["pwd2"])){
		if($_POST["pwd1"] == $_POST["pwd2"]){
			$email = $_POST["email"];
			$pwd = $_POST["pwd1"];
		}else{
			header('Location: login.php?msg=Passwords%20do%20not%20match&type=danger&ch2=1');
		}
	}else{
		header('Location: login.php?msg=Please%20fill%20all%20fields&type=danger&ch2=1');
	}
}else{
	header('Location: login.php?msg=Enter%20your%20email%20please&type=info&ch2=1');
}

if(isset($_POST["signup"])){
	if(isset($_POST["email"])&&isset($_POST["pwd"])&&isset($_POST["firstName"])&&isset($_POST["lastName"])&&isset($_POST["birth"])&&isset($_POST["address"])&&isset($_POST["city"])&&isset($_POST["state"])&&isset($_POST["zip"])&&isset($_POST["number"])){
			if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
				header('Location: profile.php?msg=Welcome%20!&type=success');
			}else{
				header('Location: login.php?msg=Please%20fill%20all%20fields&type=danger&ch2=1');
			}
	}else{
		header('Location: login.php?msg=Please%20fill%20all%20fields&type=danger&ch2=1');
	}
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ('head.html'); ?>
		
		<!-- Title website -->
		<title>Signup - Hoppy</title>
	</head>
	<body>
		<div style="background:transparent !important;" class="jumbotron">
		  <div class="container text-center">
		    <h1 class="display-3">Hoppy Learning</h1>
		  </div>
		</div>

		<?php include ('nav.php'); ?>

		<div class="container">

			<div class="page-header">
  				<h3>Create Account</h3>
  				<div class="dropdown-divider"></div>
			</div><br>
			<?php echo $msg; ?>
			<br>
		  <div class="row justify-content-center">
		  	
		    <div class="col-sm col-5">
		    	<form class="form-horizontal">
		    	  <div class="form-group">
		    	  	<!-- First Name & Last Name -->
				    <div class="row">
				    	<div class="col">
				      		<label for="exampleInputPassword1">First Name</label>
				      		<input type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="" name="firstName">
				    	</div>
				    	<div class="col">
				      		<label for="exampleInputPassword1">Last Name</label>
				      		<input type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="" name="lastName">
				    	</div>
				  	</div>
				  </div>
				  <!-- Adress -->
				  <div class="form-group">
				    <label for="inputAddress">Address</label>
				    <input type="text" class="form-control form-control-sm" id="inputAddress" placeholder="201 Avenue Charles De Gaulle" name="address">
				  </div>
				  <div class="form-group">
		    	  	<!-- City, State & Zip Code -->
				    <div class="row">
				    	<div class="col-7">
				      		<label for="inputCity">City</label>
				      		<input type="text" class="form-control form-control-sm" id="inputCity" name="city">
				    	</div>
				    	<div class="col">
						    <label for="inputState">State</label>
						    <select id="inputState" class="form-control form-control-sm" name="state">
						      <option selected>Choose...</option>
						      <option>France</option>
						    </select> 
				    	</div>
				    	<div class="col">
				      		<label for="inputZip">Zip Code</label>
				      		<input type="text" class="form-control form-control-sm" id="inputZip" name="zip">
				    	</div>
				  	</div>
				  </div>
				  <div class="form-group">
		    	  	<!-- Date of Birth & Cellphone number -->
				    <div class="row">
				    	<div class="col-7">
				      		<label for="inputCity">Date of Birth</label>
				      		<input type="date" id="birthDate" class="form-control form-control-sm" name="birth">
				    	</div>
				    	<div class="col">
						    <label for="inputState">Cellphone Number</label>
						    <input type="text" class="form-control form-control-sm" id="inputZip" name="number">
				    	</div>
				  	</div>
				  </div>

				  <!-- Email & Password for account -->
				  <div class="form-group">
				    <label for="exampleInputEmail1">Email address</label>
				    <input type="email" class="form-control form-control-sm" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="<?php echo $email;?>">
				    <small id="emailHelp" class="form-text text-muted">Your e-mail will be the id account</small>
				  </div>
				  <div class="form-group">
				    <label for="exampleInputPassword1">Password</label>
						<?php 
						if($pwd != '' && $pwd != null){
							echo '<br>';
							for($i=0;$i<strlen($pwd);$i++){
								echo '*';
							}
							echo '<br><input type="hidden" name="pwd">';
						}else{
						  echo '<input type="password" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password" name="pwd">
										<small id="passwordHelpInline" class="text-muted">
											Must be 8-20 characters long.
										</small>';
						}
						?>
				  </div>
				  <button type="submit" class="btn btn-primary btn-sm" name="signup">Submit</button>
				</form>
		    </div>
		  </div>   
		</div><br><br>

		<?php include ('footer.html'); ?>
		
	</body>
</html>