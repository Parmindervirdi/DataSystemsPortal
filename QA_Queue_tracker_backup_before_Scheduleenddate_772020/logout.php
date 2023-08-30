<?
session_start();
include "../session/session_function.php";
include './config/db_conf.php';
$conn_jira_local =mysqli_connect($Jira_local_servername, $Jira_local_username, $Jira_local_password,$Jira_local_dbname);

// Check connection
if ($conn_jira_local->connect_error) {
    die("Connection failed: " . $conn_jira_local->connect_error);
} 
//echo "Connected successfully";

destroy_sessions_db($_SESSION["username"],$conn_jira_local);

session_destroy();
if (Isset($message))
{
	$message="(wrong password combination)";
}
else 
{
	$message="";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8(no BOM)">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clever Devices - Data System Portal</title>

   	<link href="../css/style.css?version=1.2" rel="stylesheet" type="text/css">
<!--SCRIPTS-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<!--Slider-in icons-->
<script type="text/javascript">
$(document).ready(function() {
	$(".username").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".username").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
});
</script>

</head>

<body>

  <div id="wrapper_login">

	<!--SLIDE-IN ICONS-->
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
    <!--END SLIDE-IN ICONS-->

<!--LOGIN FORM-->
<form name="login-form" class="login-form" action="datasystems_Qa_queue.php" method="post">

	<!--HEADER-->
    <div class="header">
    <!--TITLE--><h1>Login DS Portal--Sucessfull Logout </h1><!--END TITLE-->
    <!--DESCRIPTION--><span>Please enter the user credentials     <?echo $message?></span><!--END DESCRIPTION-->
    </div>
    <!--END HEADER-->
	
	<!--CONTENT-->
    <div class="content">
	<!--USERNAME--><input name="username" type="text" class="input username" value="Username" onfocus="this.value=''" /><!--END USERNAME-->
    <!--PASSWORD--><input name="password" type="password" class="input password" value="Password" onfocus="this.value=''" /><!--END PASSWORD-->
    </div>
    <!--END CONTENT-->
    
    <!--FOOTER-->
    <div class="footer" aligh='center'>
    <!--LOGIN BUTTON--><input type="submit" name="submit" value="Login" class="button" /><!--END LOGIN BUTTON-->
    </div>
    <!--END FOOTER-->

</form>
<!--END LOGIN FORM-->

</div>
<!--END WRAPPER-->
</body>

</html>
