
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
			<h2>Register</h2>
			<h3> <?php
    if(!empty($success_msg)){
        echo '<p class="statusMsg">'.$success_msg.'</p>';
    }elseif(!empty($error_msg)){
        echo '<p class="statusMsg">'.$error_msg.'</p>';
    }
    ?></h3>
			<div class="w3ls-form has-feedback">
				<form action="" method="post">
					<label>User ID</label>
					  <select name="people_personId" class="s">
                                 <?php foreach ($persons as $key => $value ) {?>
                                    <option value="<?php echo $value->personId;?>"><?php echo $value->personId.'  --  '.$value->name;?></option>
                                 <?php } ?>
                      </select>
					<label>Name</label>
					<input type="text" name="userName" placeholder="USER NAME" required/>
					 <?php echo form_error('userName','<span class="help-block">','</span>'); ?>
					<label>Email</label>
					<input type="text" name="email" placeholder="EMAIL" required/>
					 <?php echo form_error('email','<span class="help-block">','</span>'); ?>
					<label>Password</label>
					<input  type="password" name="password" placeholder="Password" required />
					 <?php echo form_error('password','<span class="help-block">','</span>'); ?>
					<label>Confirm Password</label>
					<input   type="password" name="conf_password" placeholder="Confirm Password" required />
					<?php echo form_error('conf_password','<span class="help-block">','</span>'); ?>

					 <label>Category</label>
                    <select name="level" class="s">
                                 <option value="admin" >Admin</option>
                                  <option value="accounts">Accounts User</option>
                                  <option value="hr">HR User</option>
                                  <option value="design">Design Archives User</option>
                                  <option value="social">Social App User</option>
                                  <option value="projects">Projects Site User</option>
                     </select>   
                       <span class="help-block"></span>
					 <?php echo form_error('level','<span class="help-block">','</span>'); ?>
					 	

					
          			<input type="submit" name="regisSubmit" value="Submit" />
                   <a href="<?php echo base_url(); ?>user/login" class="pass">Sign In</a>
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