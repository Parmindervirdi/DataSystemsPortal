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
	$qasdate=$_POST["qadate"];
	$actdate=$_POST["Activationdate"];
	$dbpror=$_POST["priority_val"];
	$reldate=$_POST["releasedate"];
	$comm=str_replace("'",'',$_POST["comments"]);
	$stat=$_POST["dbstatus"];
	$exportnumber=$_POST["exportnumber"];
	$teamcityLink=$_POST["teamcitylink"];
	$fixedversion=$_POST["fixedversion"];
	$qaticket=$_POST["QAticket"];
	$updated_date=date("Y-m-d");
	$Stats_createtime=date("Y-m-d h:i:s");
	$cwversion=$_POST["cwversion"];
	$Schstart=$_POST["Schstart"];
	$Schend=$_POST["Schend"];
	
	
	if  ($Schstart=='')
	{
	     $Schstart='0000-00-00';
	}
	
	
	if  ($Schend=='')
	{
	     $Schend='0000-00-00';
	}
	
	
	if ($stat=='Released')
	{
		$releasedate=date("Y-m-d");
	}	
	else 
	{
		$releasedate='0000-00-00';
	}
	###pick records 
	if (isset($_POST["pick"]))
	{
		$despick=1;
	}
	else 
	{
		$despick=0;
	}
	
		if ($qasdate=='')
	{
	   $qasdate='0000-00-00';
	}
	if ($actdate=='')
	{
	   $actdate='0000-00-00';
	}
	if ($reldate=='')
	{
	   $reldate='0000-00-00';
	}
	
	##Select the Name and ID of customer 
	
	$TAname=customername($_POST["Customer"],$conn_jira_local);
	$customerID=$_POST["Customer"];
	if ($stat=='Released')
	{
	    $update_qaqueue="update DBS_QAQUEUE set QA_Startdata='$qasdate',Actination_date='$actdate',QA_Priority=100,DB_releasedate='$reldate',Comments='$comm',DB_Status='$stat',ExportNumber='$exportnumber',TeamcityLink='$teamcityLink',ReleasedDate='$releasedate',Pick=$despick,FixedVersion='$fixedversion',QA_Ticket='$qaticket',UpdatedBy='$username',UpdatedDate='$updated_date',ReleasedBY='$username',ReleasedDate='$updated_date',cleverworksversion='$cwversion' where QueuID=$qid";
	}
	else 
	{
		$update_qaqueue="update DBS_QAQUEUE set QA_Startdata='$qasdate',Actination_date='$actdate',QA_Priority='$dbpror',DB_releasedate='$reldate',Comments='$comm',DB_Status='$stat',ExportNumber='$exportnumber',TeamcityLink='$teamcityLink',ReleasedDate='$releasedate',Pick=$despick,FixedVersion='$fixedversion',QA_Ticket='$qaticket',UpdatedBy='$username',UpdatedDate='$updated_date',cleverworksversion='$cwversion',Scheduleenddate='$Schend',Schedulestartdate='$Schstart' where QueuID=$qid";
		
	}	
	mysqli_query($conn_jira_local,$update_qaqueue) or die($update_qaqueue."  this query wasnt successfull");
	
	$track_status="Insert into track_status (QueueID,Status,createdby,Createtime) values ($qid,'$stat','$username','$Stats_createtime')";
	mysqli_query($conn_jira_local,$track_status) or die($track_status."  this query wasnt successfull");
	
	
	##update Epic with Schedule start and End date 
	    ##update if the value of Schedule end and start date has been changed 
		$select_controlflow="select Scheduleenddate,Schedulestartdate from dbs_customerbase where CustomerID=$customerID";
		$execute_select_controlflow=mysqli_query($conn_jira_local,$select_controlflow);
		$row_controlflow=mysqli_fetch_array($execute_select_controlflow);
		
		$sch_start_db=$row_controlflow["Schedulestartdate"];
		$sch_end_db=$row_controlflow["Scheduleenddate"];
	
	##update cleverworks into customerbase table 
	$update_cb="update dbs_customerbase set CWVersion='$cwversion' ,updatedby='$username',updateddate=now(),Scheduleenddate='$Schend',Schedulestartdate='$Schstart'  where CustomerID=$customerID";
	//echo $update_cb;
	mysqli_query($conn_jira_local,$update_cb);
	
	
		
		//echo  $sch_start_db."      ".$sch_end_db;
		//echo  $Schstart."          ".$Schend;
		
		if ($sch_start_db!=$Schstart  ||  $sch_end_db!=$Schend)
		{
		   shell_exec("py ../../../Python_Code/update_Epic_schend_schstart.py  $fixedversion  $Schstart  $Schend");
	      //echo "python ../../../Python_Code/update_Epic_schend_schstart.py" . $fixedversion  .$Schstart  .$Schend;
		}
		
	
	
	if ($stat=='Released')
	{
	shell_exec("py ../../../Python_Code/Autoclose_fixedversion.py $fixedversion");
	shell_exec("py ../../../Python_Code/Jiracloseepic.py $fixedversion");
        ###get the Build number from Teamcity Link 
	$link_build_array=explode('=',$teamcityLink);
	$build_array=explode('&',$link_build_array[1]);
	$buildnumber=$build_array[0];
	shell_exec("py ../../../Python_Code/Buildpin.py $buildnumber $fixedversion $qaticket $cwversion");

	include("email_test.php");
    }
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
	$cwversion=$row_qa_queue[22];
	$schend=$row_qa_queue[23];
	$schstart=$row_qa_queue[24];
	$teamcitylink=$row_qa_queue[16];
	$exportnumber=$row_qa_queue[15];
	
	
   
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
								   <td class="record_odd"><input type="text" name="QAticket" <? if ($usertype=='QA'){echo "readonly";} ?> value=<?echo $QAticket;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Fixed Version</td>
								   <td class="record_even"><input type="text" name="fixedversion" <? if ($usertype=='QA'){echo "readonly";} ?> value=<?echo $FixedVersion;?>></td>
							   </tr>
							   <tr> 
							       <td class="record_odd">CleverWorks Version</td>
								   <td class="record_odd"><input type="text" name="cwversion"  value=<?echo $cwversion;?>></td>
							   </tr>
							   <tr> 
							       <td class="record_odd">QA Start date</td>
								   <td class="record_odd"><input type="date" name="qadate" <? if ($usertype=='QA'){echo "readonly";} ?> value=<?echo $qastartdate;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Activation date</td>
								   <td class="record_even"><input type="date" name="Activationdate" <? if ($usertype=='QA'){echo "readonly";} ?> value=<?echo $activationdate;?>></td>
                                                          </tr>
							   <tr> 
							       <td class="record_odd">Schedule Start Date</td>
								   <td class="record_odd"><input type="date" name="Schstart" value=<?echo $schstart;?> ></td>
							   </tr>
							   <tr> 
							       <td class="record_odd">Schedule End Date</td>
								   <td class="record_odd"><input  type="date" name="Schend" value=<?echo $schend;?>></td>
							   
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
							       <td class="record_even">Required Release Date</td>
								   <td class="record_even"><input type="date" name="releasedate" <? if ($usertype=='QA'){echo "readonly";} ?> value=<?echo $db_releasedate;?>></td>
							   </tr>
							   <tr> 
							       <td class="record_even">Pick</td>
								   <? if ($pick==1)
								   {
									   ?>
									   <td class="record_even"><input type="Checkbox" name="pick" <? if ($usertype=='QA'){echo "readonly";} ?> checked></td>
								   <?
								   }
								   else 
								   {?>
							          <td class="record_even"><input type="Checkbox" name="pick" <? if ($usertype=='QA'){echo "readonly";} ?>></td>
									 									   
								  <? }
								   ?>
								   
							   </tr>
							    <tr> 
							       <td class="record_odd">Database Comments</td>
								   <td class="record_odd">
								       <textarea rows="4" cols="30" name="comments" ><?echo $comments;?></textarea>
								   </td>
							   </tr>
							    <tr> 
							       <td class="record_even">DB Status</td>
								   <td class="record_even">
								             <select name="dbstatus" id='type'>
											                 <option><?echo $status;?></option>
											                 <option>None</option>
											                 <option>Ready Peer review</option>
															  <option>Ready QA</option>
															  <option>Emergency Database</option>
															  <option>Ready QA(Emergency Database)</option>
															  <option>On Hold</option>
															  <option>Test DB</option>
															  <?if ($usertype=='L'||$usertype=='M')
															  {?>
															  <option>Released</option>
															  <?}?>
															  <option>Work In Progress</option>
															  
														 
											 </select>			 
								   </td>
							   </tr>
							    <tr id='test'> 
							       <td class="record_odd">Export Number:</td>
								   <td class="record_odd"><input type="Text" name="exportnumber" value=<?echo $exportnumber;?>></td>
							   </tr>
							   <tr id='test1'> 
							       <td class="record_even">TeamCity Link:</td>
								   <td class="record_even"><input type="Text" name="teamcitylink" value=<?echo $teamcitylink;?>></td>
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