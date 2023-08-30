<?
include './config/db_conf.php';
include './fm_functions.php';



$customerid=$_POST["Customer"];
$backup=$_FILES["backupfile"]['name'];
$cwexport=$_FILES["CWexport"]['name'];
$QAticket=$_POST["QAticket"];
$exportno=$_POST["Exportno"];
$fixedrelease=$_POST["fixedrelease"];

$find_Cust_name=customername($customerid,$conn_jira_local);

###Load the files to the folder  
##Backup


if(isset($_FILES['backupfile'])){
      $errors= array();
      $file_name = $_FILES['backupfile']['name'];
      $file_size =$_FILES['backupfile']['size'];
      $file_tmp =$_FILES['backupfile']['tmp_name'];
      $file_type=$_FILES['backupfile']['type'];
	  
   $pathtoload=$loadfilebasedir.$find_Cust_name."/"."1_QA"."/Bustools/".$file_name; 
   $pathtounlink=$loadfilebasedir.$find_Cust_name."/"."1_QA"."/Bustools/*";   
     ##remove files from bustools folder 
	    ##first get the files 
		    $files=glob($pathtounlink);
		    foreach($files as $file){ // iterate files
              if(is_file($file))
                 unlink($file); // delete file
                }
	 if(empty($errors)==true){
         move_uploaded_file($file_tmp,$pathtoload);
         $backresult="Success";
      }else{
         print_r($errors);
      }
   }

   
###Export 

if(isset($_FILES['CWexport'])){
      $errors= array();
      $file_name_CE = $_FILES['CWexport']['name'];
      $file_size_CE =$_FILES['CWexport']['size'];
      $file_tmp_CE =$_FILES['CWexport']['tmp_name'];
      $file_type_CE=$_FILES['CWexport']['type'];
	  
	  
   $pathtoload_CE=$loadfilebasedir.$find_Cust_name."/"."1_QA"."/Export/".$file_name_CE;  
   
   $pathtounlink_CE=$loadfilebasedir.$find_Cust_name."/"."1_QA"."/Export/*";   
     ##remove files from bustools folder 
	    ##first get the files 
		    $files_CE=glob($pathtounlink_CE);
			//print_r($files_CE);
		    foreach($files_CE as $file_CE)
			{ // iterate files
              
			  if (is_Dir($file_CE))
			  {
				  rrmdir($file_CE); //function to remove the directory files
			  }
			  else 
			  {
				  unlink($file_CE);
			  }
			}
			
     if(empty($errors)==true){
         move_uploaded_file($file_tmp_CE,$pathtoload_CE);
         $zip = new ZipArchive;
         $res = $zip->open($pathtoload_CE);
		 $exportfolderpath=$loadfilebasedir.$find_Cust_name."/"."1_QA"."/Export/";
        if ($res === TRUE) 
		  {
               $zip->extractTo($exportfolderpath);
               $zip->close();
		  }
		  unlink ($pathtoload_CE);
		 $result_CE="Success";
      }else{
         print_r($errors);
      }
   }
   




?>

<html>
<head>
<title>File Mamagement</title>
	
               <link href="./css/pattern_comp.css?version=7" rel="Stylesheet"> 		 
	
</head>
<body>

        <!-- this is the Header-->
		<div class="header">
		     
		</div>
		
		<!-- this Div is for the left hand pane options -->
		<div class="leftpane">
		      
		</div>
		
		
		<!-- page heading -->
		<div class="pageheading">Loading files 
		</div>
	 
        <!-- div container for content--> 
		<div class="container">
			<div class="tablebody">
			<?
			 ####if upload is successull then add the record to the database 
			if ($backresult=="Success" && $result_CE=="Success") 
{
####insert data into database 
##inser into the details table 

$insert_datafiles="insert into fm_datafileupload (CustomerID,BackupName,Exportname,ExportNumber,QATicket,FixedRelease,date,UserID) 
                    values ($customerid,'$backup','$cwexport','$exportno','$QAticket','$fixedrelease',NOW(),1)";
if (mysqli_query($conn_jira_local,$insert_datafiles))
{
	
	##insert data for the auto run
	$insert_autorun_data="insert into fm_runjobs values($customerid)";
	mysqli_query($conn_jira_local,$insert_autorun_data);
	
		echo $find_Cust_name." Backup and export files  has been loded to the target, database is also set for the Autorun";
	
}


	 
}
	?>			
		</div>
		
</body>
</html> 