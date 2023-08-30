<?
include './config/db_conf.php';
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
	$comm=$_POST["comments"];
	$stat=$_POST["dbstatus"];
	
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
	
	
	
	$update_qaqueue="update DBS_QAQUEUE set QA_Startdata='$qasdate',Actination_date='$actdate',QA_Priority='$dbpror',DB_releasedate='$reldate',Comments='$comm',DB_Status='$stat' where QueuID=$qid";
											
	mysqli_query($conn_jira_local,$update_qaqueue) or die($update_qaqueue."  this query wasnt successfull");
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

?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=14" rel="Stylesheet"> 		 
	
</head>
<body>

<?
   ###select QAqueue records from the database so user can update the values 
   $select_qa_queue="select * from DBS_QAQUEUE where queuID=$qaqueueid";
   $execute_qa_queue=mysqli_query($conn_jira_local,$select_qa_queue);
   $row_qa_queue=mysqli_fetch_array($execute_qa_queue);
   
   $qastartdate=$row_qa_queue[2];
   $activationdate=$row_qa_queue[3];
   $dbpriority=$row_qa_queue[4];
   $db_releasedate=$row_qa_queue[5];
   $comments=$row_qa_queue[6];
   $status=$row_qa_queue[7];
   
?>

	<div class="tablebody_sub">
				<!-- table to get the values --> 
				 <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST">  
				 <table width=400 align="center">
				    <tr><td class="header">update the fields</td></tr>
					<tr>
					      <table width=400 align="center">
						       
							   <tr> 
							       <td class="record_odd">QA Start date</td>
								   <td class="record_odd"><input type="date" name="qadate" value=<?echo $qastartdate;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Activation date</td>
								   <td class="record_even"><input type="date" name="Activationdate" value=<?echo $activationdate;?>></td>
							   </tr>
							    <tr> 
							       <td class="record_odd">Database Priority</td>
								   <td class="record_odd">
								              <select name="priority_val">
											             <option><?echo $dbpriority;?></option>
														 <option value=0>No Priority</option>
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
								   <td class="record_even"><input type="date" name="releasedate" value=<?echo $db_releasedate;?>></td>
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
								             <select name="dbstatus">
											                 <option><?echo $status;?></option>
											                 <option>select Status</option>
											                 <option>Ready Peer review</option>
															  <option>Ready QA</option>
															  <option>On Hold</option>
															  <option>Test DB</option>
															  <option>Released</option>
															  <option>Work In Progress</option>
															  <option>QA in Progress</option>
															  <option>Place Holder</option>
															  <option>Duplicate</option>
														 
											 </select>			 
								   </td>
							   </tr>
							 
						  <input type=hidden name=controlflow value=1>
						  <input type=hidden name=qid value=<?echo $qaqueueid;?>>
						  </table>
					</tr>
					<tr><input type="submit" value="done" name="subval"></tr>
					    
					     
					     
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