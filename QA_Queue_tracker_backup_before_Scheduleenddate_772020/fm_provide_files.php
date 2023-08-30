<?
include './config/db_conf.php';
?>
<html>
<head>
<title>File Management</title>
	
               <link href="css/pattern_comp.css?version=11" rel="Stylesheet"> 		 
	
</head>
<body>

        <!-- this is the Header-->
		<div class="header">
		      <img class="logo" src="images/logo.png" width="200"/>
		</div>
		
		<!-- this Div is for the left hand pane options -->
		<div class="leftpane">
		      
		</div>
		
		
		<!-- page heading -->
		<div class="pageheading">Data Systems QA Queue  
		</div>
	 
        <!-- div container for content--> 
		<form  action="fm_loadfilestodatabase.php" method="POST" enctype="multipart/form-data"> 
		<div class="container">
			 <!-- div container Top header--> 
			<div class="containerheader">Data Systems QA Queue : <? Echo date("m/d/Y"); ?>   
			</div>
			<!-- div Container Table Body-->
			<div class="tablebody">
				<!-- table to get the values --> 
				 <table width=600 height="300" align="center">
				  <tr>
				     <td class="textfont">Customer</td>
					 <td class="textfont">
                       <div  style="width:200px;">				   
 					 <?              ##select the customer from the database 
									$sel_cust="select * from customers order by CustomerName";
									$execute_sel_cust=mysqli_query($conn_jira_local,$sel_cust);	
									?>
					               <select name="Customer">
						
									<option value="">Select Customer:</option>
								   
									<?
									while ($row_set_cust=mysqli_fetch_array($execute_sel_cust))
									{		
                                            $value=$row_set_cust["ID"];
                                            $cust_name=$row_set_cust["CustomerName"];											
									
								   ?>
												  <option value=<?echo $value?>><?echo $cust_name ?></option>
												  
									 <?
									}
									?>						 
						</select>
						</div>
					 </td>
				  </tr>

				   <tr>
				     <td class="textfont">CleverWorks Backup file&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
					 <td class="textfont"><input type="file" name="backupfile"/> </td>
					
				  </tr>
				   <tr>
				     <td class="textfont">CleverWorks Export&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
					 <td class="textfont"><input type="file" name="CWexport"></td>
				  </tr>
				    <tr>
				     <td class="textfont">QA Ticket Number&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
					 <td class="textfont"><input type="text" name="QAticket"></td>
				  </tr>
				    <tr>
				     <td class="textfont">Export Number&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
					 <td class="textfont"><input type="text" name="Exportno"></td>
				  </tr>
				    <tr>
				     <td class="textfont">Fixed Release&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
					 <td class="textfont"><input type="text" name="fixedrelease"></td>
				  </tr>
				 </table>
			</div>
			
		     <!-- div container button area --> 
			<div class="containerbutton"><input type="submit" value="Load files"> 
			</div>
		</div>
		</form>

</body>
</html> 