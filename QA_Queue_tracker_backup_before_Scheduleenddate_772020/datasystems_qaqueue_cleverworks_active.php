<?
Session_start();
include './config/db_conf.php';
include "../session/session_function.php";
    #check Session validity
	$username=$_SESSION['username'];
	$check_valid_logtime=validatesession($username,$conn_jira_local);
     if ($check_valid_logtime!=1)
	 {?>
      // <script>
	      window.opener.location.reload();
	      self.close();
      </script>	
		 
	 <?}
	
#self post parameters 
##post parameter and the control flow 

if (isset ($_POST["controlflow"]))
{
	$username=$_SESSION["username"];
	###update the records
    $version=$_POST["version"];
	$flow=$_POST["controlflow"];
	$updatedate=date("Y-m-d");
	
	
	$insert_qaqueue="update  dbs_customerbase set CWActivated=$flow,updateddate='$updatedate',updatedby='$username' where CWVersion like '%$version%'";	
   	echo $insert_qaqueue;
	mysqli_query($conn_jira_local,$insert_qaqueue) or die($insert_qaqueue."  this query wasnt successfull");
    


?>
	 <script>
	     window.opener.location.reload();
	      self.close();
     </script>	
<?}
else 
{

?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=15" rel="Stylesheet"> 	
</head>
<body bgcolor=#AED6F1>
<form name="1" method="POST" action=<? echo $_SERVER['PHP_SELF']; ?>>
	<div class="active_deactive">
	<button class="button_version button2_version" type='submit' >Activate</button>
	<input type="Hidden" name="controlflow" value=1>
	<input type="Hidden" name="version" value=<? echo $_GET["version"]?>>
	</form>
<form name ="2" method="POST" action=<? echo $_SERVER['PHP_SELF']; ?>>
	<button class="button_version button2_version" type='submit' >De-Activate</button>
	
	<input type="Hidden" name="controlflow" value=0>
	<input type="Hidden" name="version" value=<? echo $_GET["version"]?>>
				
		</div>
</form>

</body>
</html> 
<?
}
?>