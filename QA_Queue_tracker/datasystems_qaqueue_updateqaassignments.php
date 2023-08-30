<?
session_start();
include './config/db_conf.php';
include "../session/session_function.php";
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
<title>DataSystems Portal</title>
	
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

<script>
function validateForm() {
  var fv=document.forms["savevalues"]["dbstatus"].value;
  if (fv=="Released")
  {
	  if (document.forms["savevalues"]["exportnumber"].value=='' || document.forms["savevalues"]["teamcitylink"].value=='')
	  {
		  alert("Export Number and Teamcity Link must be filled out");
          return false;
	  }
  }
  
  
}
</script>	
	
</head>
<?
include './fm_functions.php';
#self post parameters 
##post parameter and the control flow 
if (isset ($_POST["controlflow"]))
{
	###update the records 
	$qid=$_POST['qid'];
	$comm=str_replace("'",'',$_POST["QA_comments"]);
	$stat=$_POST["Qa_dbstatus"];
	$qaperson=$_POST["QA_resource"];
	$updated_date=date("Y-m-d");	
	$Stats_createtime=date("Y-m-d h:i:s");
	
	##check the QA Done date 
	if ($stat=="QA Completed")
	{
	     $qacompletedate=date("Y-m-d h:i:s");
	}  
	else 
	{
	     $qacompletedate='00-00-0000';
	}
	
	##Select the Name and ID of customer 
	
	$TAname=customername($_POST["Customer"],$conn_jira_local);
	
	$update_qaqueue="update DBS_QAQUEUE set QA_comments='$comm',DB_Status='$stat',QA_Personal='$qaperson',UpdatedBy='$username',UpdatedDate='$updated_date',QA_Completedate='$qacompletedate' where QueuID=$qid";
											
	mysqli_query($conn_jira_local,$update_qaqueue) or die($update_qaqueue."  this query wasnt successfull");

    $track_status="Insert into track_status (QueueID,Status,createdby,Createtime) values ($qid,'$stat','$username','$Stats_createtime')";
	mysqli_query($conn_jira_local,$track_status) or die($track_status."  this query wasnt successfull");
	
?>
	 <script>
	      window.opener.location.reload();
	      self.close();
     </script>	
<?}
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
   $qastartdate=$row_qa_queue[2];
   $activationdate=$row_qa_queue[3];
   $dbpriority=$row_qa_queue[4];
   $db_releasedate=$row_qa_queue[5];
   $comments=$row_qa_queue[6];
   $status=$row_qa_queue[7];
   $QAticket=$row_qa_queue[8];
   $FixedVersion=$row_qa_queue[9];
   $pick=$row_qa_queue[14];
   $QAComments=$row_qa_queue[19];
   $qa_completedate=$row_qa_queue[20];
   $qa_Assignment=$row_qa_queue[21];
   
	
   
?>

	<div class="tablebody_sub">
				<!-- table to get the values --> 
				 <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST" onsubmit="return validateForm()">  
				 <table width=400 align="center">
				    <tr><td class="header">update the fields</td></tr>
					<tr>
					      <table width=400 align="center">
						       
							    <tr> 
							       <td class="record_odd">QA Ticket</td>
								   <td class="record_odd"><input type="text" name="QAticket" <? if ($usertype=='S'||$usertype=='R'){echo "readonly";} ?> value=<?echo $QAticket;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Fixed Version</td>
								   <td class="record_even"><input type="text" name="fixedversion" <? if ($usertype=='S'||$usertype=='R'){echo "readonly";} ?> value=<?echo $FixedVersion;?>></td>
							   </tr>
							   <tr> 
							       <td class="record_odd">QA Start date</td>
								   <td class="record_odd"><input type="date" name="qadate" <? if ($usertype=='S'||$usertype=='R'){echo "readonly";} ?> value=<?echo $qastartdate;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Activation date</td>
								   <td class="record_even"><input type="date" name="Activationdate" <? if ($usertype=='S'||$usertype=='R'){echo "readonly";} ?> value=<?echo $activationdate;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_odd">Database Priority</td>
								   <td class="record_odd">
								              <select name="priority_val">
											             <option><?echo $dbpriority;?></option>
														 <option>No Priority</option>
														 <?
														 $i=1;
														 for ($i=1;$i<=10;$i++)
														 { ?>
														     <option><?echo $i;?></option>
														 <?}
														 ?>
											</select>		 
								   </td>
							   </tr>
							    
							   <tr> 
							       <td class="record_even">Pick</td>
								   <? if ($pick==1)
								   {
									   ?>
									   <td class="record_even"><input type="Checkbox" name="pick" <? if ($usertype=='R'||$usertype=='R'){echo "readonly";} ?> checked></td>
								   <?
								   }
								   else 
								   {?>
							          <td class="record_even"><input type="Checkbox" name="pick" <? if ($usertype=='R'||$usertype=='R'){echo "readonly";} ?>></td>
									 									   
								  <? }
								   ?>
								   
							   </tr>
							   
							    <tr> 
							       <td class="record_even">Assigned To</td>
								   <td class="record_even">
								             <select name="QA_resource" id='type'>
											                 <option><?echo $qa_Assignment;?></option>
											                 <option>Apoorva Patankar</option>
															 <option>Ajith Bhaskar</option>
															 <option>Deepak Swami</option>
															  <option>Anthony Seija</option>
															 <option>Shreya Shrivas</option>
															 <option>Girija Gosavi </option>
															 <option>Subha Balsubramanian </option>
															 <option>Anas Khan</option>
																													 
											 </select>			 
								   </td>
							   </tr>
							   
							   
							    <tr> 
							       <td class="record_odd">QA team comments</td>
								   <td class="record_odd">
								       <textarea rows="4" cols="30" name="QA_comments" ><?echo $QAComments;?></textarea>
								   </td>
							   </tr>
							    <tr> 
							       <td class="record_even">DB Status</td>
								   <td class="record_even">
								             <select name="Qa_dbstatus" id='type'>
											                 <option><?echo $status;?></option>
											                 <option>None</option>
											                 <option>QA Completed</option>
															 <option>Waiting Information(QA)</option>
															 <option>QA in Progress</option>
														 
											 </select>			 
								   </td>
							   </tr>
							   							
						  <input type=hidden name=controlflow value=1>
						  <input type=hidden name=Customer value=<?echo $customer;?>>
						  <input type=hidden name=qid value=<?echo $qaqueueid;?>>
						  </table>
					</tr>
					<tr>&nbsp;</tr>
					<tr>&nbsp;</tr>
					<tr>&nbsp;</tr>
					<tr><button type='Submit' class="button_edit button2_edit" >Done</button></tr>
					    
					     
					     
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