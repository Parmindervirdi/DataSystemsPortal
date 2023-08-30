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
    $customer=$_POST["Customer"];
	$cwversion=$_POST["cwversion"];
	if ($cwversion=='')
	{
		$cwversion='';
	}
	else 
	{
		$cwversion=$cwversion;
	}
	$databaseperson=$_POST["databaseperson"];
	$comm=str_replace("'",'',$_POST["comments"]);
		
	$insert_customer="insert into dbs_customerbase (Customername,developer_assigned,createdby,cratedate,comments,CWVersion)
                                      values ('$customer','$databaseperson','$username',now(),'$comm','$cwversion')";	
   								  
	mysqli_query($conn_jira_local,$insert_customer) or die($insert_customer."  this query wasnt successfull");
    


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
	
</head>
<body>

	<div class="tablebody_version">
				<!-- table to get the values --> 
				 <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST" onsubmit="return validateForm()">  
				 <table width=400 align="center" >
				    <tr><td class="header">update the fields</td></tr>
					<tr>
					      <table width=400 align="center">
						       
							   <tr> 
							       <td class="record_odd">Customer</td>
								   <td class="record_odd"><input type="text" name="Customer">
								   </td>
							   </tr>
							   
							    <tr> <?###Add BTD-16029 Clever works version ?>
							       <td class="record_odd">CleverWorks Version</td>
								   <td class="record_odd"><input type="Text" name="cwversion" ></td>
							   </tr>
							   
							   <tr> 
							       <td class="record_odd">Database Personal</td>
								   <? 
								         #fetch the database users 
										 $select_users="select * from dbs_users order by fname";
										 $execute_users=mysqli_query($conn_jira_local,$select_users);
								   ?>
								   <td class="record_odd">
								        <select name="databaseperson">
										     <option>Select Developer</option>
											 <?while ($row_execute_users=mysqli_fetch_array($execute_users))
											 {?>
										      <option value=<?echo $row_execute_users["USERID"]?>><? echo $row_execute_users["FNAME"]." ".$row_execute_users["LNAME"]?></option>
												 
											 <?}?>
										</select>
								   </td>
							   </tr>
							   <tr> 
							       <td class="record_even">Comments(if Any)</td>
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