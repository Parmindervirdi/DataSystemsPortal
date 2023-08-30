<?
session_start();
include './config/db_conf.php';
include "../session/session_function.php";
include './fm_functions.php';
    #check Session validity
	$username=$_SESSION['username'];
	$check_valid_logtime=validatesession($username,$conn_jira_local);
     if ($check_valid_logtime!=1)
	 {?>
      <script>
	      window.opener.location.reload();
	      self.close();
      </script>	
		 
	 <?}
?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=14" rel="Stylesheet"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	
$("#test").hide();
$("#test1").hide();
	
$("#type").change(function(){
if ($("#type").val()=='Released')
{
$("#test").show();
$("#test1").show();
}
else 
{
$("#test").hide();
$("#test1").hide();
}

});
  
});
</script>

	
</head>
<?
if (isset ($_POST["controlflow"]))
{
	###update the records 
	$qid=$_POST['qid'];
	$customer=$_POST['Customer'];
	$fixedversion=$_POST['fixedversion'];
	$exportnumber=$_POST['exortno'];
	$teamcitylink=$_POST['teamcitylink'];
	$customername=$_POST['customername'];
	$qaticket=$_POST['qaticket'];
	$devnote=$_POST['Specialcomments'];
	$devnote_py='"'.$devnote.'"';
	$teamcitylink_py='"'.$teamcitylink.'"';
	##Select the Name and ID of customer 
	
	
     
	$insert_exportdata="Insert into exportcreated (DatabaseID,CustomerID,ExportNumber,TeamcityLink,DateAdded,Addedby,DevNote) values ($qid,'$customer','$exportnumber','$teamcitylink',CURDATE(),'$username','$devnote')";
	//echo $insert_exportdata;
	if (mysqli_query($conn_jira_local,$insert_exportdata))
	{
	$update_qaqueue="update DBS_QAQUEUE set ExportNumber='$exportnumber',TeamcityLink='$teamcitylink',DB_Status='Ready QA' where QueuID=$qid";
	mysqli_query($conn_jira_local,$update_qaqueue);
	shell_exec("python ../../../Jira_ticketcreate_python/Addcomments.py $qaticket $exportnumber $devnote_py $teamcitylink_py");
	//echo "python ../../../Jira_ticketcreate_python/Addcomments.py" .$qaticket." ".$exportnumber." ".$teamcitylink_py." ".$devnote_py;
	include("email_qaready.php");
	}
?>
	 <script>
	      window.opener.location.reload();
	      self.close();
     </script>	
<?
}
else 
{

#get calue of parameter 
$qaqueueid=$_GET["qaqueueid"];
$username=$_SESSION["username"];
 $loginname=whoisuser($username,$conn_jira_local);
 $usertype=$loginname[0];

?>

<body>

<?
   ###select QAqueue records from the database so user can update the values 
   $select_qa_queue="select * from DBS_QAQUEUE where queuID=$qaqueueid";
   $execute_qa_queue=mysqli_query($conn_jira_local,$select_qa_queue);
   $row_qa_queue=mysqli_fetch_array($execute_qa_queue);
   
	$customer=$row_qa_queue[1];
	$FixedVersion=$row_qa_queue[9];
	$TAname=customername($customer,$conn_jira_local);
	$QAticket=$row_qa_queue[8];
		
   
?>

	<div class="tablebody_sub">
				<!-- table to get the values --> 
				 <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST" onsubmit="return validateForm()">  
				 <table width=400 align="center">
				    <tr><td class="header">Add Export details</td></tr>
					<tr>
					      <table width=400 align="center">
						       
							  
							    <tr> 
							       <td class="record_even">Fixed Version</td>
								   <td class="record_even"><input type="text" name="fixedversion" readonly  value=<?echo $FixedVersion;?>></td>
							   </tr>
							   <tr> 
							       <td class="record_odd">Customer</td>
								   <td class="record_odd"><input type="text" name="customername" readonly  value=<?echo $TAname;?>></td>
							   </tr>
							   <tr> 
							       <td class="record_even">New Export Number</td>
								   <td class="record_even"><input type="text" name="exortno" ></td>
							   </tr>
							    <tr> 
							       <td class="record_odd">Teamcity Export Link</td>
								   <td class="record_odd"><input type="text" name="teamcitylink"</td>
							   </tr>
							   <tr> 
							       <td class="record_even">Special Note for QA </td>
								   <td class="record_even"> <textarea rows="4" cols="30" name="Specialcomments" ></textarea>
							   </tr>
							   
							
						  <input type=hidden name=controlflow value=1>
						  <input type=hidden name=Customer value=<?echo $customer;?>>
						  <input type=hidden name=qid value=<?echo $qaqueueid;?>>
						  <input type=hidden name=qaticket value=<?echo $QAticket;?>>
						  
						  </table>
					</tr>
					<tr>&nbsp;</tr>
					<tr>&nbsp;</tr>
					<tr><button type='Submit' class="button_edit button2_edit" >Submit and Send Email</button></tr>
					<tr><button type='Submit' class="button_edit button2_edit" >Submit</button></tr>
					    
					     
					     
			</table>
				 </form>
			</div>
			
		     <!-- div container button area --> 
			
			</div>
		</div>
		</form>

</body>
</html> 
<?
}
?>