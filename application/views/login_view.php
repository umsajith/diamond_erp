<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $G_title; ?></title> 
    <link rel="icon" type="image/png" href="<?php echo base_url('favicon.ico'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css" type="text/css');?>">
    <style>
    	body {margin-top: 25px; background-color: #444;}
		.login-well {background-color: #fff; padding: 20px;}
		.login {width: 400px;}
		.login-actions {margin-top: 0;}
		.login-alert {margin-bottom: 0; text-align: center; font-weight: bold;}
		#login-logo {padding: 15px; text-align: center;}
		#loginForm {margin: 0;}
    </style>
</head>
<body>
    <div class="container login">
		<div class="row-fluid">
			<div class="login-container">
				<div id="login-logo">
					<img src="<?php echo base_url('img/erp_logo_big.png'); ?>"/>
				</div>

				<div class="login-well">
					<form action="<?=site_url('login')?>" method="post" id="loginForm">
					<p class="text-center">Please enter your credentials to access the application</p>
					<hr>
						<input type="text" name="username" id="username" class="input-block-level" placeholder="Username">
						<input type="password" name="password" id="password" class="input-block-level" placeholder="Password">
					<div class="alert alert-error login-alert"></div>      
				</div>
					<div class="form-actions login-actions">
						<button type="submit" class="btn btn-primary btn-block">Login</button>
					</div>
					</form>
			</div>
		</div>
        <?php echo validation_errors(); ?>
    </div>
	<div id="flash">
		<?php  echo $this->session->flashdata('flash');?>
		<?php $this->session->unset_userdata('flash');?>
	</div>
	<script src="<?php echo base_url('js/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('js/bootstrap.min.js');?>" type="text/javascript"></script>

<script>
	$(function() {

		$(".alert").hide();
		$("#username").val("");
		$("#password").val("");
		
		$("#loginForm").on("submit",function(){
			var username = $("#username"),
				password = $("#password"),
				alertBox = $(".alert");

			if(username.val()==""){
				alertBox.html('Please provide your Username!').fadeIn();
				username.focus();
			    return false;
			}
			if(password.val()==""){
				alertBox.html('Please provide your Password!').fadeIn();
				password.focus();
			    return false;
			}

			$.post("<?php echo site_url('login'); ?>",
					   {username:username.val(),password:password.val(),ajax:"1"},
					   function(data){
						   if(data){
							  location.replace(data);
						   }
						   else {
							   alertBox.html("Authentication Failed!").fadeIn();
							   username.val("");
							   password.val("");
							   username.focus();
						   }	
					   });
			return false;
		});
	});
</script>

</body>
</html>