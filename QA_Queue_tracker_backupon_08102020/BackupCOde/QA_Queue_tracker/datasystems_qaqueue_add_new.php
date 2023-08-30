<?
include './config/db_conf.php';
#self post parameters 
##post parameter and the control flow 
if (isset ($_POST["controlflow"]))
{
	###update the records
    $customer=$_POST["Customer"];
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
	
	
	$insert_qaqueue="insert into DBS_QAQUEUE (Customer,QA_Startdata,Actination_date,QA_Priority,DB_releasedate,Comments,DB_Status)
                                      values ($customer,'$qasdate','$actdate',$dbpror,'$reldate','$comm','$stat')";	
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
	
               <link href="css/pattern_comp.css?version=14" rel="Stylesheet"> 		 
	
</head>
<body>

	<div class="tablebody_sub">
				<!-- table to get the values --> 
				 <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST">  
				 <table width=400 align="center">
				    <tr><td class="header">update the fields</td></tr>
					<tr>
					      <table width=400 align="center">
						       <?
							       ##select custmer list 
								   $select_customers="select * from dbs_customerbase order by customername";
								   $execute_customers=mysqli_query($conn_jira_local,$select_customers);
							   ?>
							   <tr> 
							       <td class="record_odd">Customer</td>
								   <td class="record_odd">
								                   <select name="Customer">
												               <option>Select Customers</option>
															   <?
														 while ($row_fetch_customer=mysqli_fetch_array($execute_customers))
														 { ?>
															   <option value=<?echo $row_fetch_customer[0];?>><?echo $row_fetch_customer[1];?></option>
															 
														 <?}
															   ?>
												   </select>
								   </td>
							   </tr>
							   <tr> 
							       <td class="record_even">QA Start date</td>
								   <td class="record_even"><input type="date" name="qadate" ></td>
							   </tr>
							    <tr> 
							       <td class="record_odd">Activation date</td>
								   <td class="record_odd"><input type="date" name="Activationdate" ></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Database Priority</td>
								   <td class="record_even">
								              <select name="priority_val">
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
							       <td class="record_odd">Required Release Date</td>
								   <td class="record_odd"><input type="date" name="releasedate" ></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Database Comments</td>
								   <td class="record_even">
								       <textarea rows="4" cols="30" name="comments" ></textarea>
								   </td>
							   </tr>
							    <tr> 
							       <td class="record_odd">DB Status</td>
								   <td class="record_odd">
								             <select name="dbstatus">
											                 
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