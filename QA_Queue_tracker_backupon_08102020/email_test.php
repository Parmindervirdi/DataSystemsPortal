
<?php
// the message
$msg = '<html>
<head>
<style>
img {
  float: left;
   border-radius: 5%;
}
p.note
{
   color:#0000b3;
   font-family:Verdana;
   font-size:12px
}
p.reg
{
   color:#000000;
   font-family:Calibri;
   font-size:14px
}

</style>
</head>
<title></title>
<body>
<p class="reg">Hello All,</p>
<p class="reg">The database has been released, please see details below</p>
<table width="1000" height="80" >

<tr>

<td width="100" height="20" align="center" Style="background-color:#b3b3b3;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">Customer</td>
<td width="300" height="20" align="center" Style="background-color:#b3b3b3;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">'.$TAname.'</td>
</tr>
<tr>

<td width="100" height="20" align="center" Style="background-color:#f2f2f2;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">Fixed Version</td>
<td width="300" height="20" align="center" Style="background-color:#f2f2f2;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">'.$fixedversion.'</td>
</tr>               
<tr>

<td width="100" height="20" align="center" Style="background-color:#b3b3b3;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">Database Export</td>
<td width="300" height="20" align="center" Style="background-color:#b3b3b3;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">'.$exportnumber.'</td>
</tr>
<tr>

<td width="100" height="20" align="center" Style="background-color:#f2f2f2;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">QA Ticket</td>
<td width="300" height="20" align="center" Style="background-color:#f2f2f2;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;"><a href="https://jira.cleverdevices.com:8443/browse/'.$qaticket.'">'.$qaticket.'</a></td>
</tr>
<tr>

<td width="100" height="20" align="center" Style="background-color:#b3b3b3;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;">Pinned database on teamcity</td>
<td width="300" height="20" align="center" Style="background-color:#b3b3b3;color:#000000;font-family:Verdana;font-size:11px; font-weight: bold;"><a href="'.$teamcityLink.'">'.$teamcityLink.'</td>
</tr>
</table>
<p class="Note"><b>****If you are a PM or SE who interacts with this customer, Data Systems wants YOUR feedback!****</b></p>
<p><a href="https://www.surveymonkey.com/r/WFP6FHJ"><img style="margin: 0; border: 0; padding: 0; display: block;" src="http://172.18.2.169:8282/phpcode/Portal/QA_Queue_tracker/images/feedback.jpg" alt="Feedback, Please" width="200" height="50" ></a></p>

</body>
</html>';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: pvirdi@CleverDevices.com' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);

// send email
$sub=$TAname." CleverWorks Export ".$exportnumber. " has been Released from QA and DataSystems";
mail($sendemailto,$sub,$msg,$headers);
?>
