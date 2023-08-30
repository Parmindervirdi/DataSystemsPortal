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
	 
#check for the pagenumbers
//print_r($_GET);
if (isset($_POST["norecs"]))
{
	$page_display_limit=$_POST["norecs"];
}
else
if (isset($_GET["norecsget"]))
	{
	$page_display_limit=$_GET["norecsget"];
	
}

?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=30" rel="Stylesheet"> 		 
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
					  <li><a href="./datasystems_Qa_queue.php">Current Queue</a></li>
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
				<?                 ##select custmer list 
								   $select_customers="select * from dbs_customerbase order by customername";
								   $select_versions="select distinct SUBSTRING(CWVersion, 1, 3)  from dbs_customerbase order by customername";
								   $execute_customers=mysqli_query($conn_jira_local,$select_customers);
								   $execute_versions=mysqli_query($conn_jira_local,$select_versions);
                                   								   
								   ?>
			
			<div class="tablebody_versions" >
				<!-- table to get the values --> 
				<div class="search-by-customers-container">Search by Customers</div>
				<div class="search-table_customer">
                  <form name="searchbycustomer" method="POST" action="fetchresults.php">				     
					 <table class="versiontable">
					          <tr>
							       <td class="record_even">Select Customer</td>
								   <td class="record_even">
								       <select name="customer">
									           <option>Customers</option>
											   <?
														 while ($row_fetch_customer=mysqli_fetch_array($execute_customers))
														 { ?>
															   <option value=<?echo $row_fetch_customer[0];?>><?echo $row_fetch_customer[1];?></option>
															 
														 <?}
															   ?>
									   </select>
								   </td class="record_even">
								   <td class="record_even"><button class="button_version button2_version" type='submit' >Go</button></td>								   
							  </tr>
					  </table>
				 </div>
				<div class="or">OR</div>
				<div class="search-by-cwversion-container">Search by CleverWorks Versions</div> 
				<div class="search-table_cwversion">
				      <table class="versiontable">
					          <tr>
							       <td class="record_even">Select CleverWorks Version</td>
								   <td class="record_even">
								       <select name="cwversion">
									           <option>CW Version</option>
											     <?
														 while ($row_fetch_versions=mysqli_fetch_array($execute_versions))
														 { ?>
															   <option value=<?echo $row_fetch_versions[0]?>><?echo $row_fetch_versions[0];?></option>
															 
														 <?}
															   ?>
									   </select>
								   </td>
								   <td class="record_even"><button class="button_version button2_version" type='submit' >Go</button></td>								   
							  </tr>
					  </table>
					  </form>
			</div>
			<div class="button_container_version">
					 <!-- div container button area --> 
					 <div class="button_area"><button class="button_version button2_version" type='button' onclick="open_newaddcust_Win()">Add New Customer</button></div> 
					
					</div>
		</div>
					
		</form>
					
					
					

</body>
</html> 
<?
}
else 
{
	$message=1;
	include_once("ds_login.php");
}
?>