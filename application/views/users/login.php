
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Meta tags -->
	<title>Indesign Interior</title>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- stylesheets -->
	<link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/logo.png">
	<link href="<?php echo base_url(); ?>static/css/css/style.css" rel='stylesheet' type='text/css' />
    <link href="<?php echo base_url(); ?>static/css/css/font-awesome.css" rel='stylesheet' type='text/css' />

	<!-- google fonts  -->
	
</head>
<body>
	<div class="agile-login">
		<h1>INDESIGN INTERIORS</h1>
		<div class="wrapper">
			<h2>Login</h2>
			<h3> <?php
    if(!empty($success_msg)){
        echo '<p class="statusMsg">'.$success_msg.'</p>';
    }elseif(!empty($error_msg)){
        echo '<p class="statusMsg">'.$error_msg.'</p>';
    }
    ?></h3>
			<div class="w3ls-form has-feedback">
				<form action="" method="post">
					<label>Email</label>
					<input type="text" name="email" placeholder="EMAIL" required/>
					 <?php echo form_error('email','<span class="help-block">','</span>'); ?>
					<label>Password</label>
					<input type="password" name="password" placeholder="Password" required />
					 <?php echo form_error('password','<span class="help-block">','</span>'); ?>
					
          			<input type="submit" name="loginSubmit" value="LogIn" />
                   <a href="<?php echo base_url(); ?>user/registration" class="pass">Sign Up</a>
				</form>
			</div>
			
			<div class="agile-icons">
				
			</div>
		</div>
		<br>
		<div class="copyright">
		<p>© 2018 Login. All rights reserved </p> 
	</div>
	</div>
	
</body>
</html>