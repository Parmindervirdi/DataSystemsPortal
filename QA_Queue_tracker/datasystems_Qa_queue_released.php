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

if (isset($_SESSION['username'])) 
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
//$isvalidlogin=1;
if ($isvalidlogin==1 && $check_valid_logtime==1)
{
	 $username=$_SESSION['username'];
	 insertsession($username,$conn_jira_local);
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
	
               <link href="css/pattern_comp.css?version=28" rel="Stylesheet"> 		 
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
					  <li><a  href="./datasystems_Qa_queue.php">Current Queue</a></li>
					  <li><a  class="active" href="./datasystems_Qa_queue_released.php">Released DBs</a></li>
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
					   $select_total_records="select * from DBS_QAQUEUE where DB_Status='Released'";
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
			<option>100</option>
			</select>
			 
			</div>
			
			<!--Div for page numbers  -->
			
			<div class="pagenumbers pagination" ><a href="#">&laquo;</a><?###Display the links to the page 
		   for($i=1;$i<=$number_of_pages;$i++)
		   {?>
			   <a href ="./datasystems_Qa_queue_released.php?page=<?echo $i;?>&isvalidlogin=1&norecsget=<? echo $page_display_limit;?>" <?if ($page==$i){ echo "class=active";}?>><? echo  " ".$i." "?></a>		   
		<?	   
		   }
      ?>
			<a href="#">&raquo;</a></div>
			<!-- div Container Table Body-->
			
			<?
			
			
?>
             </form>
             <div class="filterbody">
			 <form id="filterdata" method="POST" Action=<?echo $_SERVER['PHP_SELF'];?> > 
					 <table  width=600 >
					          <?
							       ##select custmer list 
								   $select_customers="select * from dbs_customerbase order by customername";
								   $execute_customers=mysqli_query($conn_jira_local,$select_customers);
								   //print_r($_POST);
							   ?>
								 <tr>
									<td class="filterfont">Filter by Customer</td>
									<td class="filterfont"><select name="filterCust">
												               
															   <?
															   if (isset($_POST["filterCust"]))
															   {
																 ?><option value=<?echo $_POST["filterCust"];?>>
																 <?																 
																  $select_customers_display="select * from dbs_customerbase where customerid=".$_POST["filterCust"];
								                                  $execute_customers_disp=mysqli_query($conn_jira_local,$select_customers_display); 
																  $row_fetch_customer_disp=mysqli_fetch_array($execute_customers_disp) ;
																 echo $row_fetch_customer_disp[1];
															   }
															   else {
																   ?>
																   <option value="">
																   <?
															 
															   echo "Select Customer";
															   }
															 ?>													
															   </option>
															   <?
														 while ($row_fetch_customer=mysqli_fetch_array($execute_customers))
														 { ?>
															   <option value=<?echo $row_fetch_customer[0];?>><?echo $row_fetch_customer[1];?></option>
															 
														 <?}
															   ?>
												   </select></td>
								</tr>
								<tr>			
									<td></td>
									<td class="filterfont">OR/AND</td>
									<td></td>
								 </tr>
								<tr>			
									<td class="filterfont">Filter by Date Range</td>
									<td class="filterfont">From Date :<input type="date" name="filterfromdate"></td>
									<td class="filterfont">To Date :<input type="date" name="filtertodate"></td>
								 </tr>
								 <tr>			
									<td></td>
									<td class="filterfont"><button type='Submit' class="button_edit button2_edit">Show Results</button></td>
									<td></td>
								 </tr>
					 </table>
                                   
                     </form>					 
			 </div>
			<div class="tablebody_released" >
			   
				<!-- table to get the values --> 
				 <table width=1300 align="center" id="myTable" class="tablesorter">
				      <thead>
					  <tr>
					       <td class="header" width=20 height="40">S.No</td>
						   <td class="header" width=100 height="40">Customer name</td>
						   <td class="header" width=80 height="40">QA Start Date</td>
						   <td class="header" width=80 height="40">Activation Date</td>
						   <td class="header" width=40 height="40">Database QA Priority</td>
						   <td class="header" width=80 height="40">Released Date</td>
						   <td class="header" width=300 height="40">Database Comments</td>
						   <td class="header" width=100 height="40">QA Ticket</td>
						   <td class="header" width=180 height="40">Fixed Version</td>
                           <td class="header" width=100 height="40">Team Lead</td>
						   <td class="header" width=100 height="40" >DB developer</td>
						   <td class="header" width=50 height="40" >DB Status</td>
						   <td class="header" width=50 height="40" >Action</td>
						   
				      </tr>
					  </thead>
					  	<tbody>
					  <?
					    # if the Filter records are SET 
						if (isset($_POST["filterCust"]) && $_POST["filterCust"]<>'' && $_POST["filterfromdate"]=='')
						{
							
							$filtcust=$_POST["filterCust"];
							$select_records="select * from DBS_QAQUEUE where DB_Status='Released' and Customer=$filtcust  order by queuID limit $query_limit, $page_display_limit";		   
						}
						elseif (isset($_POST["filterfromdate"]) && isset($_POST["filtertodate"]) && $_POST["filterCust"]=='')
						{
							$fromdate=$_POST["filterfromdate"];
							$todate=$_POST["filtertodate"];
							$select_records="select * from DBS_QAQUEUE where DB_Status='Released' and DB_releasedate between '$fromdate' and '$todate'  order by queuID limit $query_limit, $page_display_limit";
						}
						elseif (isset($_POST["filterfromdate"]) && isset($_POST["filtertodate"]) && isset($_POST["filterCust"]) && $_POST["filterfromdate"] <>'' && $_POST["filtertodate"] && $_POST["filterCust"]<>'' )
						{
							$filtcust=$_POST["filterCust"];
							$fromdate=$_POST["filterfromdate"];
							$todate=$_POST["filtertodate"];
							$select_records="select * from DBS_QAQUEUE where DB_Status='Released' and DB_releasedate between '$fromdate' and '$todate'  and Customer=$filtcust   order by queuID limit $query_limit, $page_display_limit";
						}						
						else
						{
							$select_records="select * from DBS_QAQUEUE where DB_Status='Released' order by queuID limit $query_limit, $page_display_limit";
						}
						//echo $select_records;
					   
					    ###retrive select result
							//$select_records="select * from DBS_QAQUEUE where DB_Status='Released' order by queuID limit $query_limit, $page_display_limit";		   
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
								$releasedate=$row_queue[17];
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
										   <td class="record_even" width=50 height="20"><? echo $releasedate;?></td>
										   <td class="record_even" width=300 height="20"><? echo $comments;?></td>
										   <td class="record_even" width=100 height="20"><a href="https://jira.cleverdevices.com:8443/browse/<? echo $QA_Ticket;?>" target="_blank"><? echo $QA_Ticket;?></a></td>
										   <td class="record_even" width=180 height="20"><? echo $fixedversion;?></td>
										   <td class="record_even" width=100 height="20"><? echo $mangname;?></td>
										   <td class="record_even" width=100 height="20"><? echo $devname;?></td>
										   <td class="record_even" width=100 height="20"><? echo $dbstatus;?></td>
										   <?
									   if ($usertype=='L' || $usertype=='M')
									   {?>	
										   <td class="record_even" width=100 height="20"><button type='button' class="button_edit button2_edit" onclick="openWin(<?echo $queueID;?> )">Edit</button></td>
				                    <?}
								   else
									   {?>
								           <td class="record_even" width=100 height="20"><img src="./images/greendot.png" height="20" width="20"></td>
									
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
										   <td class="record_Odd" width=50 height="20"><? echo $releasedate;?></td>
										   <td class="record_Odd" width=300 height="20"><? echo $comments;?></td>
										   <td class="record_Odd" width=100 height="20"><a href="https://jira.cleverdevices.com:8443/browse/<? echo $QA_Ticket;?>" target="_blank"><? echo $QA_Ticket;?></a></td>
										   <td class="record_Odd" width=180 height="20"><? echo $fixedversion;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $mangname;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $devname;?></td>
										   <td class="record_Odd" width=100 height="20"><? echo $dbstatus;?></td>
                                        <?										   
										   if ($usertype=='L' || $usertype=='M')
									   {?>	
										   <td class="record_Odd" width=100 height="20"><button type='button' class="button_edit button2_edit" onclick="openWin(<?echo $queueID;?> )">Edit</button></td>
				                    <?}
								   else
									   {?>
								           <td class="record_Odd" width=100 height="20"><img src="./images/greendot.png" height="20" width="20"></td>
									
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
