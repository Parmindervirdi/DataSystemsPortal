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
<title>DataSystems Portal</title>
	
               <link href="css/pattern_comp.css?version=25" rel="Stylesheet"> 		 
	<script>
	function openWin(qaqueueid) {
	window.open("datasystems_qaqueue_update.php?qaqueueid=" + qaqueueid,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=450,height=500");
	}
	
	function open_new_Win() {
	window.open("datasystems_qaqueue_add_new.php","_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=450,height=500");
	}
	</script>
	
<!-- Demo styling -->
	<link href="../tablesort/jqueryts/docs/css/jq.css?version=13" rel="stylesheet">

	<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
	<script src="../tablesort/jqueryts/docs/js/jquery-1.2.6.min.js"></script>

	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="../tablesort/jqueryts/css/theme.green.css" rel="stylesheet">
	<script src="../tablesort/jqueryts/js/jquery.tablesorter.min.js"></script>
	<script src="../tablesort/jqueryts/js/jquery.tablesorter.widgets.min.js"></script>
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
					  <li><a class="active" href="#home">Current Queue</a></li>
					  <li><a  href="./datasystems_Qa_queue_released.php">Released DBs</a></li>
					  <li><a href="./datasystems_Qa_queue_weekendDBs.php">Weekend Support</a></li>
					  <li><a href="./datasystems_Qa_queue_QA_Assignments.php">QA Assignments</a></li>
					   <li><a href="./datasystems_Qa_queue_cleverworksversions.php">CW Version MGMT</a></li>
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
		
	 
        <!-- div container for content--> 
		<form  method="POST" enctype="multipart/form-data"> 
		<?
		 //pagination Begin here 
					  //find how many records we have to display 
					   $while_control=1;
					  ##count how many records in total in the database 
					   $select_total_records="select * from DBS_QAQUEUE where DB_Status<>'Released'";
					   $execute_total_rec=mysqli_query($conn_jira_local,$select_total_records);
					   $total_records=mysqli_num_rows($execute_total_rec);
					     
						 
						 ####determine number of pages available 
					   $number_of_pages=ceil($total_records/$page_display_limit);
					   
					   ####determine which page user is currently on 
					   if(!isset($_GET["page"]))
					   {
						   $page=1;
					   }
					   else 
					   {
						   $page=$_GET["page"];
					   }
					    ###Determine the Limit
						$query_limit=($page-1)*$page_display_limit;		
		?>
		<div class="container">
			 <!-- div container Top header--> 
			<div class="containerheader">Data Systems QA Queue : <? Echo date("m/d/Y"); ?>   
			</div>
			<!-- div Number of records per page-->
			<div class="Numberofrecords"># recs/pg :
			<select name="norecs" onchange="this.form.submit()" >
			<option><? echo $page_display_limit;?></option>
			<option>15</option>
			<option>20</option>
			<option>25</option>
			<option>30</option>
			</select>
			 
			</div>
			
			<!--Div for page numbers  -->
			
			<div class="pagenumbers pagination" ><a href="#">&laquo;</a><?###Display the links to the page 
		   for($i=1;$i<=$number_of_pages;$i++)
		   {?>
			   <a href ="./datasystems_Qa_queue.php?page=<?echo $i;?>&isvalidlogin=1&norecsget=<? echo $page_display_limit;?>" <?if ($page==$i){ echo "class=active";}?>><? echo  " ".$i." "?></a>		   
		<?	   
		   }
      ?>
			<a href="#">&raquo;</a></div>
			<!-- div Container Table Body-->
			
			<?
			
			
?>
			<div class="tablebody" >
				<!-- table to get the values --> 
				 <table width=1300 align="center" id="myTable" class="tablesorter">
				      <thead>
					  <tr>
					       <td class="header" width=20 height="40">S.No</td>
						   <td class="header" width=100 height="40">Customer name</td>
						   <td class="header" width=80 height="40">QA Start Date</td>
						   <td class="header" width=80 height="40">Activation Date</td>
						   <td class="header" width=40 height="40">Database QA Priority</td>
						   <td class="header" width=80 height="40">Required Release date</td>
						   <td class="header" width=350 height="60">Database Comments</td>
						   <td class="header" width=100 height="40">QA Ticket</td>
						   <td class="header" width=180 height="40">Fixed Version</td>
						   <td class="header" width=50 height="40">CW Version</td>
                           <td class="header" width=100 height="40">Team Lead</td>
						   <td class="header" width=100 height="40" >DB developer</td>
						   <td class="header" width=50 height="40" >DB Status</td>
						   <td class="header" width=50 height="40" >QA Comp date</td>
						   <td class="header" width=50 height="40" >Action</td>
						   
				      </tr>
					  </thead>
					  	<tbody>
					  <?
					   
					    ###retrive select result
							$select_records="select * from DBS_QAQUEUE where DB_Status<>'Released' order by queuID limit $query_limit, $page_display_limit";		   
							$result=mysqli_query($conn_jira_local,$select_records);
                            $linecount=1;					       
						   while ($row_queue=mysqli_fetch_array($result))
							{
								$queueID=$row_queue[0];
								$customerid=$row_queue[1];
								$qastartdate=$row_queue[2];
								$dbactivationdate=$row_queue[3];
								$qapriority=$row_queue[4];
								$dbreleasedate=$row_queue[5];
								$comments=$row_queue[6];
								$dbstatus=$row_queue[7];
								$QA_Ticket=$row_queue[8];
								$fixedversion=$row_queue[9];
								$Pick_release=$row_queue[14];
								$qa_completedate=$row_queue[20];
								$Cleverworksversion=$row_queue[22];
								
								
								
								####if the date is 00-00-000 then Display TBD
								
								if ($qastartdate=='0000-00-00')
								{
								      $qastartdate='TBD';
								}
								if ($dbreleasedate=='0000-00-00')
								{
								      $dbreleasedate='TBD';
								}
								if ($dbactivationdate=='0000-00-00')
								{
								      $dbactivationdate='TBD';
								}
								
								####call for customer name 
								
								$customername=customername($customerid,$conn_jira_local);
								
								##find who is the teamlead and who is the develper 
						
								
								$dev_tl_name=whoisdev($customerid,$conn_jira_local);
								
								$array_dev_man=explode(':',$dev_tl_name);
								$devname=$array_dev_man[0];
								$mangname=$array_dev_man[1];
								
								
								if ($while_control % 2==0)
								{
									?>
								
									<tr>
									     <? if ($Pick_release==1)
										  {?>
										   <td class="record_even" width=20 height="20"><? echo $linecount;?><img src="./images/Blue_dot.png" width="10" height="10"></td>
										  <?}
										  else 
										  {?>
											 <td class="record_even" width=20 height="20"><? echo $linecount;?></td> 
										  <?}?>
										   <td class="record_even" width=100 height="20"><? echo $customername;?></td>
										   <td class="record_even" width=80 height="20"><? echo $qastartdate;?></td>
										   <td class="record_even" width=50 height="20"><? echo $dbactivationdate;?></td>
										   <td class="record_even" width=40 height="20"><? echo $qapriority;?></td>
										   <td class="record_even" width=50 height="20"><? echo $dbreleasedate;?></td>
										   <td class="record_even" width=350 height="20"><? echo $comments;?></td>
										   <td class="record_even" width=100 height="20"><a href="https://jira.cleverdevices.com:8443/browse/<? echo $QA_Ticket;?>" target="_blank"><? echo $QA_Ticket;?></a></td>
										   <td class="record_even" width=180 height="20"><? echo $fixedversion;?></td>
										   <td class="record_even" width=50 height="20"><? echo $Cleverworksversion;?></td>
										   <td class="record_even" width=100 height="20"><? echo $mangname;?></td>
										   <td class="record_even" width=100 height="20"><? echo $devname;?></td>
										   <td class="record_even" width=100 height="20"><? echo $dbstatus;?></td>
										   <td class="record_even" width=100 height="20"><? echo $qa_completedate;?></td>
										   <?
									   if ($usertype=='L' || $usertype=='M' || $usertype=='R')
									   {?>	
										   <td class="record_even" width=100 height="20"><button type='button' class="button_edit button2_edit" onclick="openWin(<?echo $queueID;?> )">Edit</button></td>
				                    <?}
								   else
									   {?>
								           <td class="record_even" width=100 height="20"><img src="./images/Scary-Face-Man-Funny-Smile.jpg" height="30" width="30"></td>
									
									   <?}?>
									
									</tr>
								<?	
								}
								else 
								{
									?>
									<tr>
									<? if ($Pick_release==1)
										  {?>
										   <td class="record_Odd" width=20 height="20"><? echo $linecount;?><img src="./images/Blue_dot.png" width="10" height="10"></td>
										  <?}
										  else 
										  {?>
											 <td class="record_Odd" width=20 height="20"><? echo $linecount;?></td> 
										  <?}?>
										   <td class="record_Odd" width=100 height="20"><? echo $customername;?></td>
										   <td class="record_Odd" width=80 height="20"><? echo $qastartdate;?></td>
										   <td class="record_Odd" width=80 height="20"><? echo $dbactivationdate;?></td>
										   <td class="record_Odd" width=40 height="20"><? echo $qapriority;?></td>
										   <td class="record_Odd" width=50 height="20"><? echo $dbreleasedate;?></td>
										   <td class="record_Odd" width=350 height="20"><? echo $comments;?></td>
										   <td class="record_Odd" width=100 height="20"><a href="https://jira.cleverdevices.com:8443/browse/<? echo $QA_Ticket;?>" target="_blank"><? echo $QA_Ticket;?></a></td>
										   <td class="record_Odd" width=180 height="20"><? echo $fixedversion;?></td>
										    <td class="record_Odd" width=50 height="20"><? echo $Cleverworksversion;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $mangname;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $devname;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $dbstatus;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $qa_completedate;?></td>
                                        <?										   
										   if ($usertype=='L' || $usertype=='M' || $usertype=='R')
									   {?>	
										   <td class="record_Odd" width=100 height="20"><button type='button' class="button_edit button2_edit" onclick="openWin(<?echo $queueID;?> )">Edit</button></td>
				                    <?}
								   else
									   {?>
								           <td class="record_Odd" width=100 height="20"><img src="./images/Scary-Face-Man-Funny-Smile.jpg" height="30" width="30"></td>
									
									   <?}?>
				                   </tr>
								   
								<?}
								
								$while_control +=1;
								
								$linecount+=1;
							}
					  ?>
					  
					     
					      
									 </tbody>  
				 </table>
			</div>
			<div class="button_container">
					 <!-- div container button area --> 
					 <div class="button_area"><button class="button button2" type='button' onclick="open_new_Win()">Add New</button></div> 
					
					</div>
		</div>
					
		</form>
					<div class="Download"><a href="Qaqueu2018.xlsx">Download the Data</a>
					</div>

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