<?
##Function to find out the Customer name
function customername ($id,$conn_jira_local)
{
	
	##select name from customers table 
	
	$select_name="select CustomerName from dbs_customerbase where customerID=$id";
	//echo $select_name;
	$execute_select_name=mysqli_query($conn_jira_local,$select_name);
	$rowfetch_execute_select_name=mysqli_fetch_array($execute_select_name);
	$name=$rowfetch_execute_select_name[0];
	return($name);
	
}

function whoisdev($databaseID,$conn_jira_local)
{
	$select_dev_name="select concat(a.FNAME,' ',a.LNAME) as devname,(select concat(FNAME,' ',LNAME) from dbs_USERS where userid=a.managerid) as Manager  from dbs_USERS a
inner join dbs_customerbase b on a.userID=b.developer_assigned where b.customerID=$databaseID";
//echo $select_dev_name;
	$execute_select_dev_name=mysqli_query($conn_jira_local,$select_dev_name);
	$rowfetch_execute_select_dev_name=mysqli_fetch_array($execute_select_dev_name);
	$dev_name=$rowfetch_execute_select_dev_name[0];
	$managername=$rowfetch_execute_select_dev_name[1];
	$namecocat=$dev_name.":".$managername;
	return($namecocat);
	
}

function whoisuser($username,$conn_jira_local)
{
	$select_user_name="select usertype,fname,lname from dbs_USERS where emailid='$username'";
//echo $select_dev_name;
	$execute_select_user_name=mysqli_query($conn_jira_local,$select_user_name);
	$rowfetch_execute_select_user_name=mysqli_fetch_array($execute_select_user_name);
	$arrayname=array();
	array_push($arrayname,$rowfetch_execute_select_user_name[0]);
	array_push($arrayname,$rowfetch_execute_select_user_name[1]);
	array_push($arrayname,$rowfetch_execute_select_user_name[2]);
	return($arrayname);
	
}


?>