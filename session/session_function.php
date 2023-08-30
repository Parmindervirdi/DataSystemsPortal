<?
#Author: Parminer singh
#PHP session functions 

function checkpassword($username,$password,$conn_jira_local)
{
	#select the username and password match 
	
	$mdcryptopassword=md5($password);
	
	$sql_query_user="select count(*) from dbs_USERS where EMAILID='$username' and PASSWORD='$mdcryptopassword'";
	$exe_sql_query_user=mysqli_query($conn_jira_local,$sql_query_user);
	$row_exe_sql_query_user=mysqli_fetch_array($exe_sql_query_user);
	
	if 	($row_exe_sql_query_user[0]>=1)
	{
		
	    return 1;
	}
	else 
	{
		 return 0;
	}
	
}


function insertsession($username,$conn_jira_local)
{
	#fetch userID from PR_Userfield 
	$sql_get_userid="select USERID,PROJECTID from dbs_USERS where EMAILID='$username'";
	//echo $sql_get_userid;
	$exe_sql_get_userid=mysqli_query($conn_jira_local,$sql_get_userid);
	$row_sql_get_userid=mysqli_fetch_array($exe_sql_get_userid);
	$projectid=$row_sql_get_userid[1];
	$userid=$row_sql_get_userid[0];
	
	$logintimeinsec=time();
		
	##check the session already present 
    $select_checksession="select count(*) as cnt  from session where projectid=$projectid and userid=$userid";
	//echo $select_checksession;
	$exe_select_checksession=mysqli_query($conn_jira_local,$select_checksession);
	$row_select_checksession=mysqli_fetch_array($exe_select_checksession);
	$number=$row_select_checksession["cnt"];
    
    if ($number >0)
	{		
                ###delete the session first and then reload 
           $delete_sesson="delete from from session where projectid=$projectid and userid=$userid"	;
           	mysqli_query($conn_jira_local,$delete_sesson);	   

             #inser into session 
				$sql_insert_session="insert into session (projectid,userid,logintime,lastclickedtime) values($projectid,$userid,$logintimeinsec,$logintimeinsec)";
				mysqli_query($conn_jira_local,$sql_insert_session);   				
	}
	else 
	{
				#insert into session 
				$sql_insert_session="insert into session (projectid,userid,logintime,lastclickedtime) values($projectid,$userid,$logintimeinsec,$logintimeinsec)";
				mysqli_query($conn_jira_local,$sql_insert_session);
		
	}
}


function validatesession($username,$conn_jira_local)
{
    #get the login time from the database 
	#fetch userID from PR_Userfield 
	$sql_get_userid="select USERID,PROJECTID from dbs_USERS where EMAILID='$username'";
	$exe_sql_get_userid=mysqli_query($conn_jira_local,$sql_get_userid);
	$row_sql_get_userid=mysqli_fetch_array($exe_sql_get_userid);
	$projectid=$row_sql_get_userid[1];
	$userid=$row_sql_get_userid[0];
	
	$sql_connect_session="select * from session where userid=$userid";
	$execute_sql_session =mysqli_query($conn_jira_local,$sql_connect_session);
	$row_select_sessiontime=mysqli_fetch_array($execute_sql_session);
	$lastclicktime=$row_select_sessiontime["lastclickedtime"];
	$curr_time=time();
	$timediff=$curr_time-$lastclicktime;
	//echo $timediff;
	if($timediff > 4000)
	
	{
		##delete the invalid session and return 0
		$delete_session="delete from session where userid=$userid";		
		mysqli_query($conn_jira_local,$delete_session); 
		session_destroy();
		return(0);
	}
	else 
	{
	    $update_lastclicktime="update session set lastclickedtime=$curr_time  where userid=$userid";
		mysqli_query($conn_jira_local,$update_lastclicktime); 
		return(1);
	}

	
}

function destroy_sessions_db($username,$conn_jira_local)
{
	#get the login time from the database 
	#fetch userID from PR_Userfield 
	$sql_get_userid="select USERID,PROJECTID from dbs_USERS where EMAILID='$username'";
	$exe_sql_get_userid=mysqli_query($conn_jira_local,$sql_get_userid);
	$row_sql_get_userid=mysqli_fetch_array($exe_sql_get_userid);
	$projectid=$row_sql_get_userid[1];
	$userid=$row_sql_get_userid[0];
	

	$delete_session_table="delete from session where  userid=$userid";
	mysqli_query($conn_jira_local,$delete_session_table); 
	
}
