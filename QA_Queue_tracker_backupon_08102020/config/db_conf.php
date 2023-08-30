<?
//phpinfo();
//echo "I am here ";
#Configiration file for the DB connect and right now I am using Access DB for fetching the Data from RAWNAV_Import Table 

//$dbs_DB = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=D:\\xampp\\htdocs\\mycode\\Parminder_utils\\MTA\\DB\\MTABltmrBT32.38_S191_MTA_V3.mdb;Uid=Admin");

//$dbs_DCT = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=D:\\xampp\\htdocs\\mycode\\Parminder_utils\\MTA\\DCT\\WRDCT_MV3.mdb;Uid=Admin");
//$connection_compare = odbc_connect("Driver={SQL Server Native Client 10.0};Server=(local);Database=Compare;", 'sa', '1qazxsw2');



#mysql connection JIRA
$servername_jira_new = "172.17.0.45";
$username_jira = "Jira-reports";
$password_jira = "Jira@CD4321";
$dbname_jira = "jira";

###MSSQL Connection from SET server 

//#Local Jira Access
//#mysql connection local
//$Jira_local_servername = "localhost:4343";
//$Jira_local_username = "root";
//$Jira_local_password = "1Qazxsw2";
//$Jira_local_dbname = "databaseportal";

#mysql connection local
$Jira_local_servername = "localhost:2020";
$Jira_local_username = "root";
$Jira_local_password = "CleverDevices1";
$Jira_local_dbname = "databaseportal";

$conn_jira_local =mysqli_connect($Jira_local_servername, $Jira_local_username, $Jira_local_password,$Jira_local_dbname);

// Check connection
if ($conn_jira_local->connect_error) {
    die("Connection failed: " . $conn_jira_local->connect_error);
} 

$loadfilebasedir='D:/xampp/htdocs/mycode/Data_System_Portal/file_management/Sample_Folder/';
$page_display_limit=15;
$sendemailto="pvirdi@CleverDevices.com,sherzig@CleverDevices.com,adonovan@cleverdevices.com,gstollberger@cleverdevices.com,rkulkarni@cleverdevices.com,ykotagiri@cleverdevices.com";

?>
