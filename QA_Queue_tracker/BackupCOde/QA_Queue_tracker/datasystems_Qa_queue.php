<?
include './config/db_conf.php';
include './fm_functions.php';
include "../session/session_function.php";
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

 

#check if the username and password are correct 

$isvalidlogin=checkpassword($username,$password,$conn_jira_local);
 //echo $isvalidlogin;
}
else 
{

//print_r($_GET);
	$username=$_GET['username'];
    $password=$_GET['password'];
	$isvalidlogin=1;
	}
//echo $isvalidlogin;

if ($isvalidlogin==1)
{
	 insertsession($username,$conn_jira_local);
	 $loginname=whoisuser($username,$conn_jira_local);
	 $fname=$loginname[1];
	 $lname=$loginname[2];
	 $user_name=$fname." ".$lname;
	 $usertype=$loginname[0];
	 


?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=15" rel="Stylesheet"> 		 
	<script>
	function openWin(qaqueueid) {
	window.open("datasystems_qaqueue_update.php?qaqueueid=" + qaqueueid,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=450,height=500");
	}
	
	function open_new_Win(username) {
	window.open("datasystems_qaqueue_add_new.php?username=" + username,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=450,height=500");
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
		      <img class="logo" src="images/logo.png" width="200" />
		</div>
		
		<!-- this Div is for the left hand pane options -->
		<div class="leftpane">
		      
		</div>
		
		
		<!-- page heading -->
		<div class="pageheading">Data Systems QA Queue  
		</div>
		<div class="usernamedisplay">welcome <?echo $user_name;?>  
		</div>
		
	 
        <!-- div container for content--> 
		<form  action="fm_loadfilestodatabase.php" method="POST" enctype="multipart/form-data"> 
		<div class="container">
			 <!-- div container Top header--> 
			<div class="containerheader">Data Systems QA Queue : <? Echo date("m/d/Y"); ?>   
			</div>
			<!-- div Container Table Body-->
			
			<?
			
			
?>
			<div class="tablebody" >
				<!-- table to get the values --> 
				 <table width=1050 align="center" id="myTable" class="tablesorter">
				      <thead>
					  <tr>
					       <td class="header" width=100 height="40">Customer name</td>
						   <td class="header" width=80 height="40">QA Start Date</td>
						   <td class="header" width=80 height="40">Activation Date</td>
						   <td class="header" width=40 height="40">Database QA Priority</td>
						   <td class="header" width=80 height="40">Required Release date</td>
						   <td class="header" width=300 height="40">Database Comments</td>
                           <td class="header" width=100 height="40">Team Lead</td>
						   <td class="header" width=100 height="40" >DB developer</td>
						   <td class="header" width=50 height="40" >DB Status</td>
						   <td class="header" width=50 height="40" >Action</td>
						   
				      </tr>
					  </thead>
					  	<tbody>
					  <?
					  //pagination Begin here 
					  //find how many records we have to display 
					   $while_control=1;
					  ##count how many records in total in the database 
					   $select_total_records="select * from DBS_QAQUEUE where DB_Status Not in ('Released' ,'Duplicate') ";
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
					    ###retrive select result
							$select_records="select * from DBS_QAQUEUE where DB_Status Not in ('Released' ,'Duplicate')order by QA_Priority=0 limit $query_limit, $page_display_limit";		   
							$result=mysqli_query($conn_jira_local,$select_records);
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
										   <td class="record_even" width=100 height="30"><? echo $customername;?></td>
										   <td class="record_even" width=50 height="30"><? echo $qastartdate;?></td>
										   <td class="record_even" width=50 height="30"><? echo $dbactivationdate;?></td>
										   <td class="record_even" width=40 height="30"><? echo $qapriority;?></td>
										   <td class="record_even" width=50 height="30"><? echo $dbreleasedate;?></td>
										   <td class="record_even" width=300 height="30"><? echo $comments;?></td>
										   <td class="record_even" width=100 height="30"><? echo $mangname;?></td>
										   <td class="record_even" width=100 height="30"><? echo $devname;?></td>
										   <td class="record_even" width=100 height="30"><? echo $dbstatus;?></td>
										   <?
									   if ($usertype=='L' || $usertype=='M')
									   {?>	
										   <td class="record_even" width=100 height="30"><input type="button" value="Edit" onclick="openWin(<?echo $queueID;?> )"></td>
				                    <?}
								   else
									   {?>
								           <td class="record_even" width=100 height="30">Only for TLs</td>
									
									   <?}?>
									
									</tr>
								<?	
								}
								else 
								{
									?>
									<tr>
										   <td class="record_Odd" width=100 height="30"><? echo $customername;?></td>
										   <td class="record_Odd" width=50 height="30"><? echo $qastartdate;?></td>
										   <td class="record_Odd" width=80 height="30"><? echo $dbactivationdate;?></td>
										   <td class="record_Odd" width=40 height="30"><? echo $qapriority;?></td>
										   <td class="record_Odd" width=50 height="30"><? echo $dbreleasedate;?></td>
										   <td class="record_Odd" width=300 height="30"><? echo $comments;?></td>
										   <td class="record_Odd" width=100 height="30"><? echo $mangname;?></td>
										   <td class="record_Odd" width=100 height="30"><? echo $devname;?></td>
										   <td class="record_Odd" width=100 height="30"><? echo $dbstatus;?></td>
                                        <?										   
										   if ($usertype=='L' || $usertype=='M')
									   {?>	
										   <td class="record_Odd" width=100 height="30"><input type="button" value="Edit" onclick="openWin(<?echo $queueID;?> )"></td>
				                    <?}
								   else
									   {?>
								           <td class="record_Odd" width=100 height="30">Only for TLs</td>
									
									   <?}?>
				                   </tr>
								   
								<?}
								
								$while_control +=1;
								
								
							}
					  ?>
					  
					     
					      
					 </tbody>    
					 <tr>
		           <?###Display the links to the page 
		   for($page=1;$page<=$number_of_pages;$page++)
		   {?>
			   <a href ="datasystems_Qa_queue.php?page=<?echo $page;?>&isvalidlogin=1&username=<?echo $username;?>&password=<?echo $password;?>"><? echo  " ".$page." "?></a>		   
		<?	   
		   }
      ?>
					 </tr>
				 </table>
			</div>
			<div class="button_container">
		     <!-- div container button area --> 
			 <div class="button_area"><input type="button" value="Add New" onclick="open_new_Win()"></div> 
			
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