<?
session_start();
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
?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=17" rel="Stylesheet"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
function openWin(Qaqueuid) {
	window.open("datasystems_PeerReview_add_checklist_dev.php?qaqueueid="+ Qaqueuid,"_blank","toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=600");
	}
</script>

</head>
<?
include './fm_functions.php';
#self post parameters 
##post parameter and the control flow 
if (isset ($_POST["controlflow"]))
{
	//print_r($_POST);
	###update the records 
	$customerID=$_POST['customerID'];
	$developer=$_POST["devassigned"];
	if (isset($_POST["dbtype"]))
{
	$databasetype=$_POST["dbtype"];
}
 else 
 {
	 $databasetype=1;
}
	if ($databasetype==1)
		{
			$busware=1;
			$cleverware=0;
		}
		else 
		{
			$busware=0;
			$cleverware=1;
		}
	$cwversion=$_POST["cwversion"];
	if (isset($_POST["Trasfers"]))
	{
	   $transfers=$_POST["Trasfers"];
	}
	else 
	{
	   $transfers=0;
	}
	if (isset($_POST["PSAs"]))
	{
	   	$psas=$_POST["PSAs"];
	}
	else
	{
		$psas=0;
	}
	if(isset($_POST["schint"]))
	{
		$schedintegration=$_POST["schint"];
	}
	else 
	{
		$schedintegration=0;
	}
	if (isset($_POST["BusTime"]))
	{
		$bustime=$_POST["BusTime"];
	}
	else
	{
		$bustime=0;
	}
	
		if (isset($_POST["cadavl"]))
	{
		$cadavl=$_POST["cadavl"];
	}
	else
	{
		$cadavl=0;
	}
	
		if (isset($_POST["farebox"]))
	{
		$farebox=$_POST["farebox"];
	}
	else
	{
		$farebox=0;
	}
	
		if (isset($_POST["Cmess"]))
	{
		$canneddata=$_POST["Cmess"];
	}
	else
	{
		$canneddata=0;
	}
	
		if (isset($_POST["Geofences"]))
	{
		$gefence=$_POST["Geofences"];
	}
	else
	{
		$gefence=0;
	}
	
		if (isset($_POST["tsps"]))
	{
		$tsps=$_POST["tsps"];
	}
	else
	{
		$tsps=0;
	}
	
		if (isset($_POST["tcps"]))
	{
		$tcps=$_POST["tcps"];
	}
	else
	{
		$tcps=0;
	}
	
		if (isset($_POST["preposttrips"]))
	{
		$preposttrips=$_POST["preposttrips"];
	}
	else
	{
		$preposttrips=0;
	}
	
		if (isset($_POST["headway"]))
	{
		$headway=$_POST["headway"];
	}
	else
	{
		$headway=0;
	}
	
		if (isset($_POST["manualroutes"]))
	{
		$manualroutes=$_POST["manualroutes"];
	}
	else
	{
		$manualroutes=0;
	}
	
		if (isset($_POST["destcodes"]))
	{
		$destcodes=$_POST["destcodes"];
	}
	else
	{
		$destcodes=0;
	}
	
		if (isset($_POST["lbas"]))
	{
		$lbas=$_POST["lbas"];
	}
	else
	{
		$lbas=0;
	}
	
		if (isset($_POST["multlang"]))
	{
		$multlang=$_POST["multlang"];
	}
	else
	{
		$multlang=0;
	}
	
		if (isset($_POST["tts"]))
	{
		$tts=$_POST["tts"];
	}
	else
	{
		$tts=0;
	}
	
		if (isset($_POST["tws"]))
	{
		$tws=$_POST["tws"];
	}
	else
	{
		$tws=0;
	}
	
		if (isset($_POST["stopreq"]))
	{
		$stopreq=$_POST["stopreq"];
	}
	else
	{
		$stopreq=0;
	}
	
		if (isset($_POST["multidestcode"]))
	{
		$multidestcode=$_POST["multidestcode"];
	}
	else
	{
		$multidestcode=0;
	}
	
		if (isset($_POST["numdepots"]))
	{
		$numdepots=$_POST["numdepots"];
	}
	else
	{
		$numdepots=0;
	}

		if (isset($_POST["numtestroutes"]))
	{
		$numtestroutes=$_POST["numtestroutes"];
	}
	else
	{
		$numtestroutes=0;
	}
	
		if (isset($_POST["numstops"]))
	{
		$numstops=$_POST["numstops"];
	}
	else
	{
		$numstops=0;
	}
	
		if (isset($_POST["numroutes"]))
	{
		$numroutes=$_POST["numroutes"];
	}
	else
	{
		$numroutes=0;
	}
	
		if (isset($_POST["majhubs"]))
	{
		$majhubs=$_POST["majhubs"];
	}
	else
	{
		$majhubs=0;
	}
	if (isset($_POST["gtfs"]))
	{
		$gtfs=$_POST["gtfs"];
	}
	else
	{
		$gtfs=0;
	}
	
	

	    $update_qaqueue="update dbs_customerbase set developer_assigned=$developer,CWVersion='$cwversion',updateddate=now(),updatedby='$username' where CustomerID=$customerID ";
	    mysqli_query($conn_jira_local,$update_qaqueue) or die($update_qaqueue."  this query wasnt successfull");
	
	#select customer record from the summary data 
	$select_summ_custrecord="select count(*) as cnt from dbs_customerdetails where Customer=$customerID";
	//echo $select_summ_custrecord;
	$execute_summ_custrecord=mysqli_query($conn_jira_local,$select_summ_custrecord) or die($select_summ_custrecord."  this query wasnt successfull");
	$row_fetch_summ_custrecord=mysqli_fetch_array($execute_summ_custrecord);
	$record_exist=$row_fetch_summ_custrecord["cnt"];
	if ($record_exist==1)
	{
		#update 
		$update_data="UPDATE dbs_customerdetails
					SET
					hastransfers = $transfers,
					hasPSAs = $psas,
					hasScheudle = $schedintegration,
					hasbustime = $bustime,
					hasCAD = $cadavl,
					hasFareBox = $farebox,
					hasCanneddate = $canneddata,
					hasgeofences = $gefence,
					hasTSPs = $tsps,
					hasTCPs = $tcps,
					hasPrePosttrips = $preposttrips,
					hasheadways = $headway,
					hasmanualrotues = $manualroutes,
					hasLBAs = $lbas,
					hasstoprequested = $stopreq,
					hasotherlanguage = $multlang,
					hasTurnWarning = $tws,
					numberofroutes = $numroutes,
					numberoftestrotues = $numtestroutes,
					numberofstops = $numstops,
					numberofdepots = $numdepots,
					numberofhubs = $majhubs,
					destint = $destcodes,
					multileveldest = $multidestcode,
					updateddate = now(),
					updatedby = '$username',
					texttospeech = $tts,
					cleverware = $cleverware,
					Busware = $busware,
					GTFS=$gtfs
					WHERE  Customer=$customerID";
		
		$execute_insert=mysqli_query($conn_jira_local,$update_data);			
	}
	else
	{
		#insert
		$insert_newrec_summ="INSERT INTO dbs_customerdetails
								(Customer,
								hastransfers,
								hasPSAs,
								hasScheudle,
								hasbustime,
								hasCAD,
								hasFareBox,
								hasCanneddate,
								hasgeofences,
								hasTSPs,
								hasTCPs,
								hasPrePosttrips,
								hasheadways,
								hasmanualrotues,
								hasLBAs,
								hasstoprequested,
								hasotherlanguage,
								hasTurnWarning,
								numberofroutes,
								numberoftestrotues,
								numberofstops,
								numberofdepots,
								numberofhubs,
								destint,
								Globalsdocs,
								multileveldest,
								createddate,
								createdby,
								Status,
								texttospeech,
								cleverware,
								Busware,
								gtfs)
								VALUES
								($customerID,
								$transfers,
								$psas,
								$schedintegration,
								$bustime,
								$cadavl,
								$farebox,
								$canneddata,
								$gefence,
								$tsps,
								$tcps,
								$preposttrips,
								$headway,
								$manualroutes,
								$lbas,
								$stopreq,
								$multlang,
								$tws,
								$numroutes,
								$numtestroutes,
								$numstops,
								$numdepots,
								$majhubs,
								$destcodes,
								1,
								$multidestcode,
								now(),
								'$username',
								1,
								$tts,
								$cleverware,
								$busware,
								$gtfs)";
   //echo $insert_newrec_summ;
   
         $execute_insert=mysqli_query($conn_jira_local,$insert_newrec_summ);
	}
	?>
	 <script>
	      window.opener.location.reload();
	      self.close();
     </script>	
<?}
else 
{

#get value of parameter 
$customer=$_GET["customer"];
$username=$_SESSION["username"];
$loginname=whoisuser($username,$conn_jira_local);
$usertype=$loginname[0];

?>

<body>

<?
   ###select QAqueue records from the database so user can update the values 
   $select_customers="select * from dbs_customerbase a
                      inner join  dbs_users b on a.developer_assigned=b.userid 
                      left join dbs_customerdetails c on a.customerID=c.Customer where a.CustomerID=$customer"; 
   $execute_customers=mysqli_query($conn_jira_local,$select_customers);
   $row_customers=mysqli_fetch_array($execute_customers);
   
   $customerID=$row_customers[0];
   $customername=$row_customers[1];
   $DeveloperID=$row_customers[2];
   $CleverWorksversion=$row_customers[3];
   $comments=$row_customers[7];
   $develpername=$row_customers[15]." ".$row_customers[16];
   $hastransfers=$row_customers[28];
   $hasPSAs=$row_customers[29];
   $hasScheudle=$row_customers[30];
	$hasbustime=$row_customers[31];
	$hasCAD=$row_customers[32];
	$hasFareBox=$row_customers[33];
	$hasCanneddate=$row_customers[34];
	$hasgeofences=$row_customers[35];
	$hasTSPs=$row_customers[36];
	$hasTCPs=$row_customers[37];
	$hasPrePosttrips=$row_customers[38];
	$hasheadways=$row_customers[39];
	$hasmanualrotues=$row_customers[40];
	$hasLBAs=$row_customers[41];
	$hasstoprequested=$row_customers[42];
	$hasotherlanguage=$row_customers[43];
	$hasTurnWarning=$row_customers[44];
	$numberofroutes=$row_customers[45];
	$numberoftestrotues=$row_customers[46];
	$numberofstops=$row_customers[47];
	$numberofdepots=$row_customers[48];
	$numberofhubs=$row_customers[49];
	$destint=$row_customers[50];
	$Globalsdocs=$row_customers[51];
	$multileveldest=$row_customers[52];
	$texttospeech=$row_customers[56];
	$cw=$row_customers[57];
	$bw=$row_customers[58];
	$gt=$row_customers[61];
		
   
?>

	<div class="tablebody_sub_createpr">
				<!-- table to get the values --> 
				  <form name="savevalues" Action=<?echo $_SERVER['PHP_SELF'];?> method="POST">  
				 <table width=700 align="center">
				    <tr><td class="header">Edit Customer Information</td></tr>
					<tr>
					      <table width=700 align="center">
						       
							    <tr> 
								   <td class="record_odd boldtext" >Customer :</td>
								   <td class="record_odd"><?echo $customername;?></td>
							     </tr>
							    <tr> 
								   
							      <td class="record_even boldtext">Developer/Change Developer:</td>
								   <td class="record_even"><?echo $develpername?>
								   <select name="devassigned">
										<option value=<?echo $DeveloperID; ?>>Change Developer</option>
									   <?
										$select_users="select userid,concat(Fname,' ',Lname) as name from dbs_users";
										echo $select_users;
										$execute_query_select_users=mysqli_query($conn_jira_local,$select_users);
										while ($row_users=mysqli_fetch_array($execute_query_select_users))
											{?>
												 <option value=<?echo $row_users["userid"];?>><?echo $row_users["name"];?></option>										
										  <?}
										?>
									</select>	
								   </td> 
							          
							   </tr>
							    <tr> 
								   <td class="record_odd boldtext" >Database Type</td>
								   <td class="record_odd"><input type="radio" name="dbtype" value=1 <?if ($bw==1){?>checked<?}?>>Busware <input type="radio" name="dbtype" value=0 <?if ($cw==1){?>checked<?}?>>CleverWare</td>
							     </tr>
								 <tr> 
								   <td class="record_odd boldtext" >CleverWorks Version</td>
								   <td class="record_odd"><input type="text" name="cwversion" value=<?echo $CleverWorksversion;?>></td>
							     </tr>
											
						  </table>
					</tr>
					<tr>
					      <table width=700 align="center">
									   <tr><td class="header">Add the Attributes</td></tr>
									   <tr>
										  <td class="record_odd "><input type="checkbox" name="Trasfers" value=1 <? if ($hastransfers==1){?> checked<?}?>></td>
										  <td class="record_odd boldtext">Trasfers</td>
										  <td class="record_odd"><input type="checkbox" name="PSAs" value=1 <? if ($hasPSAs==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">PSAs</td>
										  <td class="record_odd"><input type="checkbox" name="schint" value=1 <? if ($hasScheudle==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">Schedule Integration</td>
										  <td class="record_odd"><input type="checkbox" name="BusTime" value=1 <? if ($hasbustime==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">BusTime </td>
										  <td class="record_odd"><input type="checkbox" name="cadavl" value=1 <? if ($hasCAD==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">CAD/AVL</td>
										 
										</tr>
										<tr>
										  <td class="record_even"><input type="checkbox" name="farebox" value=1 <? if ($hasFareBox==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">Farebox Integration </td>
										  <td class="record_even "><input type="checkbox" name="Cmess" value=1 <? if ($hasCanneddate==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">Canned Messages </td>
										  <td class="record_even"><input type="checkbox" name="Geofences" value=1 <? if ($hasgeofences==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">Geofences </td>
										  <td class="record_even"><input type="checkbox" name="tsps" value=1 <? if ($hasTSPs==1){?>checked<?}?>></td>
										  <td class="record_even boldtext"> TSPs</td>
										  <td class="record_even"><input type="checkbox" name="tcps" value=1 <? if ($hasTCPs==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">TCP</td>
										  
										</tr>
										<tr>
										  <td class="record_odd"><input type="checkbox" name="preposttrips" value=1 <? if ($hasPrePosttrips==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">Pre/Post Trip Inspections </td>
										  <td class="record_odd"><input type="checkbox" name="headway" value=1 <? if ($hasheadways==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">Headway-Based Routevars </td>
										  <td class="record_odd"><input type="checkbox" name="manualroutes" value=1 <? if ($hasmanualrotues==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">Manual Logon Rt:vars </td>
										  <td class="record_odd"><input type="checkbox" name="destcodes" value=1 <? if ($destint==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">Dest code Intg</td>
										  <td class="record_odd"><input type="checkbox" name="multidestcode" value=1 <? if ($multileveldest==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">Multiple dest code supp</td>
												
										</tr>
										<tr>
										  
										  <td class="record_even"><input type="checkbox" name="lbas" value=1 <? if ($hasLBAs==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">LBAs</td>
										  <td class="record_even"><input type="checkbox" name="stopreq" value=1 <? if ($hasstoprequested==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">Stop Requested</td>
										  <td class="record_even"><input type="checkbox" name="multlang" value=1 <? if ($hasotherlanguage==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">Multiple Lang</td>
										  <td class="record_even"><input type="checkbox" name="tts" value=1 <? if ($texttospeech==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">TTS</td>
										  <td class="record_even"><input type="checkbox" name="tws" value=1 <? if ($hasTurnWarning==1){?>checked<?}?>></td>
										  <td class="record_even boldtext">TWS(Turn warn sys)</td>
											
										</tr>
										<tr>
                                          <td class="record_odd"><input type="checkbox" name="gtfs" value=1 <? if ($gt==1){?>checked<?}?>></td>
										  <td class="record_odd boldtext">GTFS</td>
												
										</tr>										
						  </table>
					</tr>
					<tr>
					      <table width=700 align="center">
									   <tr><td class="header">Add the Numbers</td></tr>
									 <tr>
											   <td class="record_odd boldtext">Depots# :</td>
											   <td class="record_odd"><input class="textlen" size="8" type="text" name="numdepots" value=<? if($numberofdepots==''){echo 0;}else {echo $numberofdepots;}?> ></td>
											   <td class="record_odd boldtext">Test routes# :</td>
											   <td class="record_odd"><input class="textlen" size="8" type="text" name="numtestroutes" value=<? if($numberoftestrotues==''){echo 0;}else {echo $numberoftestrotues;}?>></td>
											   <td class="record_odd boldtext">Unique Stops# :</td>
											   <td class="record_odd"><input  class="textlen" size="8" type="text" name="numstops" value=<? if($numberofstops==''){echo 0;}else {echo $numberofstops;}?>> </td>
											   
									   </tr>
									     <tr>
											   <td class="record_even boldtext">Routes# :</td>
											   <td class="record_even"><input  class="textlen" size="8" type="text" name="numroutes" value=<? if($numberofroutes==''){echo 0;}else {echo $numberofroutes;}?>></td>
											   <td class="record_even boldtext">Major Hubs# :</td>
											   <td class="record_even"><input  class="textlen" size="8" type="text" name="majhubs" value=<? if($numberofhubs==''){echo 0;}else {echo $numberofhubs;}?>> </td>
									   </tr>
						 </table>
					
					</tr>
					
					
					
					
					<input type="hidden" name="controlflow" value=1>
					<input type="hidden" name="customerID" value=<?echo $customerID;?>>
					
					<tr><button type='Submit' class="button_edit button2_edit" >Done</button></tr>
					    
					     
					     
				 </table>
				 </form>

</body>
</html> 
<?
}
?>