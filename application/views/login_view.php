<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <title><?php echo $G_title; ?></title> 
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css" type="text/css" media="screen" /> 
</head>
<body id="login_body">
    <div id="loginpage">
        <div id="erp_login_logo"><img src="<?php echo base_url(); ?>assets/erp_logo_big.png"/></div>
    <p><i>Please enter your credentials to access the application</i></p>
        <?php echo form_open('auth/login',"id='loginform'"); ?>
        <table id="login">
        	<tr>
        		<td class="label"><?php echo form_label('Username:','username')?></td>
        		<td><?php echo form_input('username','',"id='username'");?></td>
        	</tr>
        	<tr>
        		<td class="label"><?php echo form_label('Password:','password')?></td>
        		<td><?php echo form_password('password','',"id='password'");?></td>
        	</tr>
        	<tr>
        		<td>&nbsp;</td>
        		<td align="right"><?php echo form_submit('submit','Login',"id='button_submit'");?></td>
        	</tr>
        </table>  
        <?php echo form_close(); ?>
        <?php echo validation_errors(); ?>

    </div>
	<div id="flash">
		<?php  echo $this->session->flashdata('flash');?>
		<?php $this->session->unset_userdata('flash');?>
	</div>
<div id="login_footer">Copyright © 2011 Marko Aleksic. All Rights Reserved.</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function() {
		$("#flash").hide();
		$("#username").val("");
		$("#password").val("");
		
		$("#loginform").on("submit",function(){

			var username = $("#username"),
				password = $("#password"),
				flash = $("#flash");

			if(username.val()==""){
				flash.html("Username Required").fadeIn();
				username.focus();
			    return false;
			}
			if(password.val()==""){
				flash.html("Password Required").fadeIn();
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
							   flash.html("Acceess Denied!").fadeIn();
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