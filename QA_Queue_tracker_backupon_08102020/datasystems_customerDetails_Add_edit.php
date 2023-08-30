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
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=16" rel="Stylesheet"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
function openWin(Qaqueuid) {
	window.open("datasystems_PeerReview_add_checklist_dev.php?qaqueueid="+ Qaqueuid,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=600");
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
	$Stats_createtime=date("Y-m-d h:i:sa");
	
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
	##Select the Name and ID of customer 
	
	$TAname=customername($_POST["Customer"],$conn_jira_local);
	if ($stat=='Released')
	{
	    $update_qaqueue="update DBS_QAQUEUE set QA_Startdata='$qasdate',Actination_date='$actdate',QA_Priority='$dbpror',DB_releasedate='$reldate',Comments='$comm',DB_Status='$stat',ExportNumber='$exportnumber',TeamcityLink='$teamcityLink',ReleasedDate='$releasedate',Pick=$despick,FixedVersion='$fixedversion',QA_Ticket='$qaticket',UpdatedBy='$username',UpdatedDate='$updated_date',ReleasedBY='$username',ReleasedDate='$updated_date' where QueuID=$qid";
	}
	else 
	{
		$update_qaqueue="update DBS_QAQUEUE set QA_Startdata='$qasdate',Actination_date='$actdate',QA_Priority='$dbpror',DB_releasedate='$reldate',Comments='$comm',DB_Status='$stat',ExportNumber='$exportnumber',TeamcityLink='$teamcityLink',ReleasedDate='$releasedate',Pick=$despick,FixedVersion='$fixedversion',QA_Ticket='$qaticket',UpdatedBy='$username',UpdatedDate='$updated_date' where QueuID=$qid";
		
	}	
	mysqli_query($conn_jira_local,$update_qaqueue) or die($update_qaqueue."  this query wasnt successfull");
	
	$track_status="Insert into track_status (QueueID,Status,createdby,Createtime) values ($qid,'$stat','$username','$Stats_createtime')";
	mysqli_query($conn_jira_local,$track_status) or die($track_status."  this query wasnt successfull");
	
	if ($stat=='Released')
	{
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

#get value of parameter 
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
	
	
   
?>

	<div class="tablebody_sub_createpr">
				<!-- table to get the values --> 
				  <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST">  
				 <table width=700 align="center">
				    <tr><td class="header">Review/Add the Customer Details</td></tr>
					<tr>
					      <table width=700 align="center">
						       
							    <tr> 
								   <td class="record_odd boldtext" >Customer : <?echo $customer;?></td>
								
							    </tr>
							    
							   
						  </table>
					</tr>
					<tr>
					      <table width=700 align="center">
									   <tr><td class="header">Add the Attributes</td></tr>
									   <tr>
										  <td class="record_odd "><input type="checkbox" name="Trasfers" value=1></td>
										  <td class="record_odd boldtext">Trasfers</td>
										  <td class="record_odd"><input type="checkbox" name="PSAs" value=1></td>
										  <td class="record_odd boldtext">PSAs</td>
										  <td class="record_odd"><input type="checkbox" name="schint" value=1></td>
										  <td class="record_odd boldtext">Schedule Integration</td>
										  <td class="record_odd"><input type="checkbox" name="BusTime" value=1></td>
										  <td class="record_odd boldtext">BusTime </td>
										  <td class="record_odd"><input type="checkbox" name="cadavl" value=1></td>
										  <td class="record_odd boldtext">CAD/AVL</td>
										 
										</tr>
										<tr>
										  <td class="record_even"><input type="checkbox" name="farebox" value=1></td>
										  <td class="record_even boldtext">Farebox Integration </td>
										  <td class="record_even "><input type="checkbox" name="Cmess" value=1></td>
										  <td class="record_even boldtext">Canned Messages </td>
										  <td class="record_even"><input type="checkbox" name="Geofences" value=1></td>
										  <td class="record_even boldtext">Geofences </td>
										  <td class="record_even"><input type="checkbox" name="tsps" value=1></td>
										  <td class="record_even boldtext"> TSPs</td>
										  <td class="record_even"><input type="checkbox" name="tcps" value=1></td>
										  <td class="record_even boldtext">TCP</td>
										  
										</tr>
										<tr>
										  <td class="record_odd"><input type="checkbox" name="preposttrips" value=1></td>
										  <td class="record_odd boldtext">Pre/Post Trip Inspections </td>
										  <td class="record_odd"><input type="checkbox" name="headway" value=1></td>
										  <td class="record_odd boldtext">Headway-Based Routevars </td>
										  <td class="record_odd"><input type="checkbox" name="manualroutes" value=1></td>
										  <td class="record_odd boldtext">Manual Logon Rt:vars </td>
										  <td class="record_odd"><input type="checkbox" name="destcodes" value=1></td>
										  <td class="record_odd boldtext">Dest code Intg</td>
										  <td class="record_odd"><input type="checkbox" name="multidestcode" value=1></td>
										  <td class="record_odd boldtext">Multiple dest code supp</td>
												
										</tr>
										<tr>
										  
										  <td class="record_even"><input type="checkbox" name="lbas" value=1></td>
										  <td class="record_even boldtext">LBAs</td>
										  <td class="record_even"><input type="checkbox" name="stopreq" value=1></td>
										  <td class="record_even boldtext">Stop Requested</td>
										  <td class="record_even"><input type="checkbox" name="multlang" value=1></td>
										  <td class="record_even boldtext">Multiple Lang</td>
										  <td class="record_even"><input type="checkbox" name="tts" value=1></td>
										  <td class="record_even boldtext">TTS</td>
										  <td class="record_even"><input type="checkbox" name="tws" value=1></td>
										  <td class="record_even boldtext">TWS(Turn warn sys)</td>
											
										</tr>	
                                        <tr>
										  
										  <td class="record_odd"><input type="checkbox" name="lbas" value=1></td>
										  <td class="record_odd boldtext">Clever Works/Bustools ?</td>
										  <td class="record_odd"><input type="checkbox" name="stopreq" value=1></td>
										  <td class="record_odd boldtext">Bustime ?</td>
										  <td class="record_odd"><input type="checkbox" name="multlang" value=1></td>
										  <td class="record_odd boldtext">CAD ?</td>
										  <td class="record_odd"><input type="checkbox" name="tts" value=1></td>
										  <td class="record_odd boldtext">Clever Reports ?</td>
										  <td class="record_odd"><input type="checkbox" name="tws" value=1></td>
										  <td class="record_odd boldtext">TWS(Turn warn sys)</td>
											
										</tr>											
						  </table>
					</tr>
					<tr>
					      <table width=700 align="center">
									   <tr><td class="header">Add the Numbers</td></tr>
									   <tr>
											   <td class="record_odd boldtext">Depots# :</td>
											   <td class="record_odd"><input class="textlen" size="8" type="text" name="numdepots"></td>
											   <td class="record_odd boldtext">Test routes# :</td>
											   <td class="record_odd"><input class="textlen" size="8" type="text" name="numtestroutes"></td>
											   <td class="record_odd boldtext">Unique Stops# :</td>
											   <td class="record_odd"><input  class="textlen" size="8" type="text" name="numstops"> </td>
											   
									   </tr>
									     <tr>
											   <td class="record_even boldtext">Routes# :</td>
											   <td class="record_even"><input  class="textlen" size="8" type="text" name="numroutes"></td>
											   <td class="record_even boldtext">Major Hubs# :</td>
			      							   <td class="record_even"><input  class="textlen" size="8" type="text" name="majhubs"> </td>
									   		   <td class="record_even boldtext">CW/BT  Version</td>
											   <td class="record_even"><input  class="textlen" size="8" type="text" name="CW Version"> </td>
</tr>
						 </table>
					
					</tr>
					
					
					
					
					
					<tr><button type='button' class="button_edit button2_edit" onclick="openWin(<?echo $qaqueueid;?>)">Add Insert Checklist</button></tr>
					<tr><button type='Submit' class="button_edit button2_edit" >Done</button></tr>
					    
					     
					     
				 </table>
				 </form>

</body>
</html> 
<?
}
?>