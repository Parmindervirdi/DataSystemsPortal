
<?php
// the message
$msg = '<html>
<head>
<style>
p.note
{
   color:#0000b3;
   font-family:Verdana;
   font-size:12px
}
p.reg
{
   color:#0000b3;
   font-family:Verdana;
   font-size:14px
}


</style>
</head>
<title></title>
<body>
<p class="reg">Hello All,</p>
<p class="reg">The database work is completed and is now ready for QA and Peer review , please see details below</p>
<table width="1000" height="200">

<tr>

<td width="200" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Database Status</td>
<td width="300" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">READY FOR QA AND PEER REVIEW</td>
<tr>

<td width="200" height="20" align="center" Style="background-color:#6699cc;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Customer</td>
<td width="300" height="20" align="center" Style="background-color:#6699cc;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">'.$customername.'</td>
</tr>
<tr>

<td width="200" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Fixed Version</td>
<td width="300" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">'.$fixedversion.'</td>
</tr>               
<tr>

<td width="200" height="20" align="center" Style="background-color:#6699cc;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Database Export</td>
<td width="300" height="20" align="center" Style="background-color:#6699cc;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">'.$exportnumber.'</td>
</tr>
<tr>

<td width="200" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">QA Ticket</td>
<td width="300" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;"><a href="https://jira.cleverdevices.com:8443/browse/'.$qaticket.'">'.$qaticket.'</a></td>
</tr>
<tr>

<td width="200" height="20" align="center" Style="background-color:#6699cc;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Teamcity Database Link</td>
<td width="300" height="20" align="center" Style="background-color:#6699cc;color:white;font-family:Verdana;font-size:11px; font-weight: bold;"><a href="'.$teamcitylink.'">'.$teamcitylink.'</td>
</tr>

<tr>

<td width="200" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Database Developer</td>
<td width="300" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">'.$devname.'</td>
</tr>
<tr>

<td width="200" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">Requested Test Type</td>
<td width="300" height="20" align="center" Style="background-color:#8cb3d9;color:white;font-family:Verdana;font-size:11px; font-weight: bold;">'.$testemailtype.'</td>
</tr>

</table>
<hr width="500" align="left">
<hr width="500" align="left">
<hr width="500" align="left">
<hr width="500" align="left">
<table width="1000" height="30" style="background-color:#d9e6f2;color:black;border:1px;border-radius:10px">

<tr  width="1000" height="30" style="font-family:Verdana;font-size:11px;">
  <b>Notes from Developer :-</b> '.$devnote.'

 </tr>
</table>
</body>
</html>';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From:pvirdi@CleverDevices.com' ."\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);

// send email
$sub="DB READY QA : ".$customername." Release ".$fixedversion." and Export ".$exportnumber. " has been completed  by  Development Team  and is ready for QA and Peer Review";
//mail($sendemailto,$sub,$msg,$headers);

###username is the email who loggen in ####
####check is the export is created first time , if no the send an email to all else send it to devs and Tls 
$select_no_of_export="select * from exportcreated where DatabaseID=$qid";
$exec_select_no_of_export=mysqli_query($conn_jira_local,$select_no_of_export);
$num_of_rec=mysqli_num_rows($exec_select_no_of_export);
if ($num_of_rec==1)
{
   $emaillist=$username.",".$qasendemail;
}
else
{
   $emaillist=$username.",".$sendemailto;
}


if(mail($emaillist,$sub,$msg,$headers))
{
	echo "Mail Sent";
}
else 
{
	echo "Mail not sent";
}
?>
