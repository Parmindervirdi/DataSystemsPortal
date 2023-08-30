<?
Session_start();
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
	
#self post parameters 
##post parameter and the control flow 
if (isset ($_POST["controlflow"]))
{
	$username=$_SESSION["username"];
	###update the records
    $customer_arr=explode(':',$_POST["Customer"]);
	$customer=$customer_arr[0];
	$qasdate=$_POST["qadate"];
	$actdate=$_POST["Activationdate"];
	$dbpror=$_POST["priority_val"];
	$reldate=$_POST["releasedate"];
	$comm=str_replace("'",'',$_POST["comments"]);
	$stat="New";
	$QA_ticket=$_POST["QAticket"];
	$FixedVersion=trim($_POST["Fixedversion"]);
	$create_date=date("Y-m-d");
	##Ticket BTD-BTD-16029: Add Clever works version
	$cleverworksversion=$_POST["cwversion"];
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
	
	$insert_qaqueue="insert into DBS_QAQUEUE (Customer,QA_Startdata,Actination_date,QA_Priority,DB_releasedate,Comments,DB_Status,FixedVersion,QA_Ticket,Pick,CreatedBy,CreatedDate,CleverWorksVersion)
                                      values ($customer,'$qasdate','$actdate',$dbpror,'$reldate','$comm','$stat','$FixedVersion','$QA_ticket',$despick,'$username','$create_date','$cleverworksversion')";	
   								  
	mysqli_query($conn_jira_local,$insert_qaqueue) or die($insert_qaqueue."  this query wasnt successfull");
    
    ##update cleverworks into customerbase table 
	$update_cb="update dbs_customerbase set CWVersion='$cleverworksversion' where CustomerID=$customer";
	mysqli_query($conn_jira_local,$update_cb)

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
<title>DataSystems Portal</title>
	
               <link href="css/pattern_comp.css?version=14" rel="Stylesheet"> 

<script>
function validateForm()
{
  var x = document.forms["savevalues"]["Customer"].value;
  if (x == ""||x=="Select Customers") 
  {
    alert("Customer Name must be filled out");
    return false;
  }
}
</script>
<script>
function addvalue_Cleverworks(e) 
    {
	var cust=e.target.value
	cust=cust.split(":")
	CW_version=cust[1]
	document.getElementById("getdata").value = CW_version
}		   
</script>	
</head>
<body>

	<div class="tablebody_sub">
				<!-- table to get the values --> 
				 <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST" onsubmit="return validateForm()">  
				 <table width=400 align="center" >
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
								                   <select name="Customer" onchange="addvalue_Cleverworks(event)">
												               <option>Select Customers</option>
															   <?
														 while ($row_fetch_customer=mysqli_fetch_array($execute_customers))
														 { ?>
															   <option value=<?echo $row_fetch_customer[0].':'.$row_fetch_customer[3];?>><?echo $row_fetch_customer[1];?></option>
															 
														 <?}
															   ?>
												   </select>
								   </td>
							   </tr>
							   <tr> 
							       <td class="record_odd">Fixed Version</td>
								   <td class="record_odd"><input type="text" name="Fixedversion" ></td>
							   </tr>
							   <tr> 
							       <td class="record_even">QA Ticket</td>
								   <td class="record_even"><input type="Text" name="QAticket" ></td>
							   </tr>
							   <tr> <?###Add BTD-16029 Clever works version ?>
							       <td class="record_odd">CleverWorks Version</td>
								   <td class="record_odd"><input id="getdata" type="Text" name="cwversion" ></td>
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
											          <option value=99>No Priority</option> <?#Comments posted on 12/03/2019 : Parminder changed the default Priority to 99?>
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
							       <td class="record_odd">Pick?</td>
								   <td class="record_odd"><input type="checkbox" name="pick" ></td>
							   </tr>
							    <tr> 
							       <td class="record_even">Database Comments</td>
								   <td class="record_even">
								       <textarea rows="4" cols="30" name="comments" ></textarea>
								   </td>
							   </tr>
							    							 
						  <input type=hidden name=controlflow value=1>
						  </table>
					</tr>
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