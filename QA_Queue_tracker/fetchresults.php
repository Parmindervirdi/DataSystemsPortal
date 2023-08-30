<?
session_start();

include './config/db_conf.php';
include './fm_functions.php';
include "../session/session_function.php";
include 'Paginator.class.php';
include "download_qaq.php";
$conn_jira_local =mysqli_connect($Jira_local_servername, $Jira_local_username, $Jira_local_password,$Jira_local_dbname);

// Check connection
if ($conn_jira_local->connect_error) {
    die("Connection failed: " . $conn_jira_local->connect_error);
} 
//echo "Connected successfully";


//print_r($_POST);

if (isset($_POST['username']) && isset($_POST['password']))
{
	$username=$_POST['username'];
    $password=$_POST['password'];
#check if the username and password are correct  and Insert the session 
$isvalidlogin=checkpassword($username,$password,$conn_jira_local);
		if ($isvalidlogin==1)
		{
			insertsession($username,$conn_jira_local);
			$check_valid_logtime=validatesession($username,$conn_jira_local);
		}
}
elseif (isset($_SESSION['username']))
{
	$username=$_SESSION['username'];
	$check_valid_logtime=validatesession($username,$conn_jira_local);
	if ($check_valid_logtime==1)
	{
	     $isvalidlogin=1;
	}
	else 
	{
		$isvalidlogin=0;
	}
}
else 
{
	$isvalidlogin=0;
}
//echo $isvalidlogin;

if ($isvalidlogin==1 && $check_valid_logtime==1) #check for the session and the validity of the session log
{ 
     $_SESSION['username']=$username;
	 $loginname=whoisuser($username,$conn_jira_local);
	 $fname=$loginname[1];
	 $lname=$loginname[2];
	 $user_name=$fname." ".$lname;
	 $usertype=$loginname[0];

###post parameters from go 
$customer=$_POST["customer"];
$version=$_POST["cwversion"];

//print_r ($_POST);
	if ($customer!='Customers' && $version=='CW Version')	 
	{
#
?>
<html>
<head>
<title>Data Systems</title>
	
               <link href="css/pattern_comp.css?version=33" rel="Stylesheet"> 		 
	<script>
	
	function open_newaddcust_Win() {
	window.open("datasystems_qaqueue_add_new_Customer.php","_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=300");
	}
	</script>
	
<!-- Demo styling -->
	<link href="../../tablesort/jqueryts/docs/css/jq.css?version=13" rel="stylesheet">

	<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
	<script src="../../tablesort/jqueryts/docs/js/jquery-1.2.6.min.js"></script>

	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="../../tablesort/jqueryts/css/theme.green.css" rel="stylesheet">
	<script src="../../tablesort/jqueryts/js/jquery.tablesorter.min.js"></script>
	<script src="../../tablesort/jqueryts/js/jquery.tablesorter.widgets.min.js"></script>
	<script>
	$(function(){
		$('table').tablesorter({
			widgets        : ['zebra', 'columns'],
			usNumberFormat : false,
			sortReset      : true,
			sortRestart    : true
		});
	});
	
	 function open_window_edit_cust(customer)
	 {
		window.open("datasystems_qaqueue_edit_customers.php?customer="+ customer,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=400,left=500,top=300");
	 
	 }
	</script>
	
</head>
<body>

        <!-- this is the Header-->
		<div class="header">
		      <img class="logo" src="images/logo.png" width="200"/>
		</div>
		
		<!-- this Div is for the left hand pane options -->
		<div class="leftpane">
		      <div class="navigationcontainer">
				  <ul>
					  <li><a  href="./datasystems_Qa_queue.php">Current Queue</a></li>
					  <li><a  href="./datasystems_Qa_queue_released.php">Released DBs</a></li>
					  <li><a href="./datasystems_Qa_queue_weekendDBs.php">Weekend Support</a></li>
					  <li><a href="./datasystems_Qa_queue_QA_Assignments.php">QA Assignments</a></li>
					  <li><a class="active"  href="./datasystems_Qa_queue_cleverworksversions.php">CW Version MGMT</a></li>
					  <li><a href="#about">About</a></li>
					  <li><a href="./logout.php">Logout</a></li>
				 </ul>
			 </div>
		</div>
		
		
		<!-- page heading -->
		<div class="pageheading">Data Systems QA Queue  
		</div>
		<div class="usernamedisplay">welcome:: <?echo $user_name;?>  
		</div>
			<div class="container">
			 <!-- div container Top header--> 
			<div class="containerheader">CleverWorks versions and Customers    
			</div>
				<? 
				                   $select_customer_name="select Customername from dbs_customerbase where CustomerID =$customer";
								   $execute_customer_name=mysqli_query($conn_jira_local,$select_customer_name);
								   $row_fetch_customername=mysqli_fetch_array($execute_customer_name);
								   $customername=$row_fetch_customername["Customername"];
			?>
			<div class="tablebody_versions" >
				<div class="versionnumber">
				     <?echo $customername;?>
				</div>
				<div class="versiontable">
							<table width=500 align="center" id="myTable" class="tablesorter">
								  <thead>
								  <tr>
									   <td class="header" width=50 height="30">S.No</td>
									   <td class="header" width=170 height="30">Customer name</td>
									   <td class="header" width=200 height="30">CleverWorks Version</td>
									   <td class="header" width=200 height="30">Developer</td>
									   <td class="header" width=200 height="30">TeamLead</td>
									   <td class="header" width=80 height="30">Action</td>
								 </tr>
								  </thead>
								  <tbody>
								          <?                 ##select custmer list 
								   $select_customers="select * from dbs_customerbase where CustomerID =$customer";
								   $execute_customers=mysqli_query($conn_jira_local,$select_customers);
								   $i=1;
								   $dbdeveloper_arr=explode(':',whoisdev($customer,$conn_jira_local));
								   $dbdeveloper=$dbdeveloper_arr[0];
								   $Teamlead=$dbdeveloper_arr[1];
								   while($row_version_cust=mysqli_fetch_array($execute_customers)) 
								   {?>
											  <? if ($i%2==0)
											  {?>
											
											  <tr> 
											     <? if ($row_version_cust[6]==1)
													{?>
														<td class="record_even" width=50 height="10"><?echo $i;?><img src="./images/greendot.png" width="10" height="10"></td>
												  <?}
													else 
													{?>
														<td class="record_even" width=50 height="10"><?echo $i;?><img src="./images/reddot.png" width="10" height="10"></td>
													<?}
													?>
											   <td class="record_even" width=170 height="10"><?echo $row_version_cust[1];?></td>
											   <td class="record_even" width=200 height="10"><?echo $row_version_cust[3];?></td>
										       <td class="record_even" width=200 height="10"><?echo $dbdeveloper;?></td>
										       <td class="record_even" width=200 height="10"><?echo $Teamlead;?></td>
											   <td class="record_even" width=80 height=20><button class="button_version button2_version" type='button' OnClick="open_window_edit_cust(<?echo $customer;?>)">Edit</button></td>
                                      
											  </tr>
										   <?}
										   else 
										   {?>
												<tr> 
											     <? if ($row_version_cust[6]==1)
													{?>
														<td class="record_Odd" width=50 height="10"><?echo $i;?><img src="./images/greendot.png" width="10" height="10"></td>
												  <?}
													else 
													{?>
														<td class="record_Odd" width=50 height="10"><?echo $i;?><img src="./images/reddot.png" width="10" height="10"></td>
													<?}
													?>
											   <td class="record_Odd" width=170 height="10"><?echo $row_version_cust[1];?></td>
											   <td class="record_Odd" width=200 height="10"><?echo $row_version_cust[3];?></td>
											   <td class="record_Odd" width=200 height="10"><?echo $dbdeveloper;?></td>
										       <td class="record_Odd" width=200 height="10"><?echo $Teamlead;?></td>
											   <td class="record_Odd"  width=80 height=20><button class="button_version button2_version" type='button' OnClick="open_window_edit_cust(<?echo $customer;?>)" >Edit</button></td>                                      
											  </tr> 
										   <?}
									 $i=$i+1;
							       }
								   ?>
								  </tbody>
							</table>
							<? # get the Customer's data 
							$select_customer_summary="select * from dbs_customerdetails where customer=$customer";
							$execute_cust_summ=mysqli_query($conn_jira_local,$select_customer_summary);
							$row_execute_cust_summ=mysqli_fetch_array($execute_cust_summ);
							?>
                         
				</div>
					<div class="customer_summary">
					             <table width=1300 align="center" id="myTablesumm" class="tablesorter">
								  <thead>
								  <tr>
								     
									   <td class="header" width=50 height="30">Busware</td>
									   <td class="header" width=50 height="30">Cleverware</td>
									   <td class="header" width=50 height="30">Transfers</td>
									   <td class="header" width=50 height="30">PSAs</td>
									   <td class="header" width=50 height="30">Schedule Integration</td>
									   <td class="header" width=50 height="30">BusTime</td>
									   <td class="header" width=50 height="30">CAD/AVL</td>
									   <td class="header" width=50 height="30">Geofences</td>
									   <td class="header" width=50 height="30">Canned Messages</td>
									   <td class="header" width=50 height="30">TSPs</td>
									   <td class="header" width=50 height="30">TCP</td>
									   <td class="header" width=50 height="30">FareBox</td>
									   <td class="header" width=50 height="30">Pre/Post Trip Inspections</td>
									   <td class="header" width=50 height="30">Headway-Based Routevars</td>
									   <td class="header" width=50 height="30">Manual Logon Rt:vars</td>
									   <td class="header" width=50 height="30">Multiple dest code supp</td>
									   <td class="header" width=50 height="30">Stop Requested</td>
									   <td class="header" width=50 height="30">Multiple Lang</td>
									   <td class="header" width=50 height="30">TTS</td>
									   <td class="header" width=50 height="30">TWS(Turn warn sys)</td>
									   <td class="header" width=50 height="30">GTFS</td>
								 </tr>
								  </thead>
                                  <tbody>
								        <tr>
									    <?if ($row_execute_cust_summ[32]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									   <?if ($row_execute_cust_summ[31]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									   <?if ($row_execute_cust_summ[2]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									    <?if ($row_execute_cust_summ[3]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									  
									    <?if ($row_execute_cust_summ[4]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									   
									     <?if ($row_execute_cust_summ[5]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									   
									     <?if ($row_execute_cust_summ[6]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[9]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[8]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[10]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[11]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									  <?if ($row_execute_cust_summ[7]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[12]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[13]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[14]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[26]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[16]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[17]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[30]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									     <?if ($row_execute_cust_summ[18]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									  <?if ($row_execute_cust_summ[35]==1)
									  {?>
									   <td class="record_Odd" width=50 height="30"><img src=./images/checkmark.png width=20 height=20></td>
									  <?}
									  else 
									  {?>
										  <td class="record_Odd" width=50 height="30"><img src=./images/redcross.png width=20 height=20></td>
									  <?}?>
									  
								 </tr>
                                  </tbody>								  
                            </table>								  
					</div>
					<div class="customer-summ-numbers">
						 <table width=600 align="center" id="myTablesumm_numbers" class="tablesorter">
									  <thead>
										  <tr>
											 
											   <td class="header" width=50 height="30">Depots</td>
											   <td class="header" width=50 height="30">Test routes</td>
											   <td class="header" width=50 height="30">Unique Stops</td>
											   <td class="header" width=50 height="30">Routes</td>
											   <td class="header" width=50 height="30">Major Hubs</td>
										</tr>
									  </thead>
									  <tbody>
										   <tr>
											 
											   <td class="record_Odd" width=50 height="30"><?echo $row_execute_cust_summ[22];?></td>
											   <td class="record_Odd" width=50 height="30"><?echo $row_execute_cust_summ[20];?></td>
											   <td class="record_Odd" width=50 height="30"><?echo $row_execute_cust_summ[21];?></td>
											   <td class="record_Odd" width=50 height="30"><?echo $row_execute_cust_summ[19];?></td>
											   <td class="record_Odd" width=50 height="30"><?echo $row_execute_cust_summ[23];?></td>
										  </tr>
									  </tbody>
						 </table>			  
					</div>
			</div>
			
		</div>				

</body>
</html> 
<?
	}
     else 
	     {?>
	         <html>
<head>
<title>Data Systems</title>
	
               <link href="css/pattern_comp.css?version=32" rel="Stylesheet"> 		 
	<script>
	
	function open_newaddcust_Win() {
	window.open("datasystems_qaqueue_add_new_Customer.php","_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=300");
	}
	</script>
	
<!-- Demo styling -->
	<link href="../../tablesort/jqueryts/docs/css/jq.css?version=13" rel="stylesheet">

	<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
	<script src="../../tablesort/jqueryts/docs/js/jquery-1.2.6.min.js"></script>

	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="../../tablesort/jqueryts/css/theme.green.css" rel="stylesheet">
	<script src="../../tablesort/jqueryts/js/jquery.tablesorter.min.js"></script>
	<script src="../../tablesort/jqueryts/js/jquery.tablesorter.widgets.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script>
	$(function(){
		$('table').tablesorter({
			widgets        : ['zebra', 'columns'],
			usNumberFormat : false,
			sortReset      : true,
			sortRestart    : true
		});
	});
	</script>
	<script>
	 function open_window(versionno)
	 {
		 
	    window.open("datasystems_qaqueue_cleverworks_active.php?version="+ versionno,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=200,height=30,left=500,top=300");
	
	 }
	 function open_window_edit_cust(customer)
	 {
		window.open("datasystems_qaqueue_edit_customers.php?customer="+ customer,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=400,left=500,top=300");
	 
	 }
</script>
	</script>
	
</head>
<body>

        <!-- this is the Header-->
		<div class="header">
		      <img class="logo" src="images/logo.png" width="200"/>
		</div>
		
		<!-- this Div is for the left hand pane options -->
		<div class="leftpane">
		      <div class="navigationcontainer">
				  <ul>
					  <li><a  href="./datasystems_Qa_queue.php">Current Queue</a></li>
					  <li><a  href="./datasystems_Qa_queue_released.php">Released DBs</a></li>
					  <li><a href="./datasystems_Qa_queue_weekendDBs.php">Weekend Support</a></li>
					  <li><a href="./datasystems_Qa_queue_QA_Assignments.php">QA Assignments</a></li>
					  <li><a class="active"  href="./datasystems_Qa_queue_cleverworksversions.php">CW Version MGMT</a></li>
					  <li><a href="#about">About</a></li>
					  <li><a href="./logout.php">Logout</a></li>
					  <li><a href="./datasystems_PeerReview_Display.php">Peer Reviews</a></li>
				 </ul>
			 </div>
		</div>
		
		
		<!-- page heading -->
		<div class="pageheading">Data Systems QA Queue  
		</div>
		<div class="usernamedisplay">welcome:: <?echo $user_name;?>  
		</div>
			<div class="container">
			 <!-- div container Top header--> 
			<div class="containerheader">CleverWorks versions and Customers    
			</div>
				
			
			<div class="tablebody_versions" >
				<div class="versionnumber" style="cursor:pointer" onclick="open_window(<?echo $version;?>)">
				    <?echo $version;?>
				</div>
				<div class="versiontable">
							<table width=500 align="center" id="myTable" class="tablesorter">
								  <thead>
								  <tr>
									   <td class="header" width=50 height="30">S.No</td>
									   <td class="header" width=170 height="30">Customer name</td>
									   <td class="header" width=200 height="30">CleverWorks Version</td>
									   <td class="header" width=80 height="30">Action</td>
								 </tr>
								  </thead>
								  <tbody>
								          <?                 ##select custmer list 
								   $select_customers="select * from dbs_customerbase where CWVersion like '%$version%'";
								   $execute_customers=mysqli_query($conn_jira_local,$select_customers);
								   $i=1;
								   while($row_version_cust=mysqli_fetch_array($execute_customers)) 
								   {?>
											  <? if ($i%2==0)
											  {?>
											
											  <tr> 
											        <? if ($row_version_cust[6]==1)
													{?>
														<td class="record_even" width=50 height="10"><?echo $i;?><img src="./images/greendot.png" width="10" height="10"></td>
												  <?}
													else 
													{?>
														<td class="record_even" width=50 height="10"><?echo $i;?><img src="./images/reddot.png" width="10" height="10"></td>
													<?}
													?>
											   
											   <td class="record_even" width=170 height="10"><?echo $row_version_cust[1];?></td>
											   <td class="record_even" width=200 height="10"><?echo $row_version_cust[3];?></td>
										       <td class="record_even" width=80 height=20><button class="button_version button2_version" type='button' OnClick="open_window_edit_cust(<?echo $row_version_cust[0];?>)">Edit</button></td>
                                      
											  </tr>
										   <?}
										   else 
										   {?>
												<tr> 
											     <? if ($row_version_cust[6]==1)
													{?>
														<td class="record_Odd" width=50 height="10"><?echo $i;?><img src="./images/greendot.png" width="10" height="10"></td>
												  <?}
													else 
													{?>
														<td class="record_Odd" width=50 height="10"><?echo $i;?><img src="./images/reddot.png" width="10" height="10"></td>
													<?}
													?>
											   <td class="record_Odd" width=170 height="10"><?echo $row_version_cust[1];?></td>
											   <td class="record_Odd" width=200 height="10"><?echo $row_version_cust[3];?></td>
											   <td class="record_Odd"  width=80 height=20><button class="button_version button2_version" type='button' OnClick="open_window_edit_cust(<?echo $row_version_cust[0];?>)">Edit</button></td>                                      
											  </tr> 
										   <?}
									 $i=$i+1;
							       }
								   ?>
								  </tbody>
							</table>	  
				</div>
			</div>
			
		</div>				

		</body>
		</html> 
	            
         <?}
}
 else 
	{
		$message=1;
		include_once("ds_login.php");
	}


?>