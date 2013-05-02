<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title><?=$G_title?></title> 
    <link rel="icon" type="image/png" href="<?=base_url('favicon.ico')?>">
    <link rel="stylesheet" href="<?=base_url('css/bootstrap.min.css')?>">
    <style>
    	body {margin-top: 25px; background-color: #444;}
    	.shadow{-moz-box-shadow: 3px 3px 4px #222;-webkit-box-shadow: 3px 3px 4px #222;box-shadow: 3px 3px 4px #222;}
		.login-well {background-color: #fff; padding: 20px;}
		.login {width: 360px;}
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
					<img src="<?=base_url('img/erp_logo_big.png')?>"/>
				</div>
				<div class="shadow">
					<div class="login-well">
						<form action="<?=site_url('login')?>" method="post" id="loginForm">
						<p class="text-center">Please enter your credentials to access the application</p>
						<hr>
							<input type="text" name="username" id="username" class="input-block-level" placeholder="Username">
							<input type="password" name="password" id="password" class="input-block-level" placeholder="Password">
						<div class="alert alert-error login-alert"></div>
						<?=uif::load('_validation')?>
					</div>
					<div class="form-actions login-actions">
						<button type="submit" class="btn btn-primary input-medium pull-right"><strong>Login</strong></button>
					</div>
						</form>
					</div>
				</div>
		</div>
        
    </div>
	<script src="<?=base_url('js/jquery.js')?>"></script>
    <script src="<?=base_url('js/bootstrap.min.js')?>"></script>

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

				$.ajax({
					url: "<?=site_url('login')?>",
					type: 'post',
					dataType: 'json',
					data: {username:username.val(),password:password.val()},
					success : function(data) {
						document.location.href = data.redirect;
					},
					error : function() {
						alertBox.html("Authentication Failed!").fadeIn();
						username.val("").focus();
						password.val("");
					}
				});

				return false;
			});
		});
	</script>

</body>
</html>