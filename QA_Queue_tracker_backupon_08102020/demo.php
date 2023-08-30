<?

include './config/db_conf.php';?>
<html>
   
   <head>
      <title>Paging Using PHP</title>
   </head>
   
   <body>
     <?php
           ##define how much records we want to display 
		   $record_per_page=10;
		   
		   ##count how many records in total in the database 
		   $select_total_records="select * from DBS_QAQUEUE where DB_Status<>'Released'";
		   $execute_total_rec=mysqli_query($conn_jira_local,$select_total_records);
		   $total_records=mysqli_num_rows($execute_total_rec);
		   
		   ####determine number of pages available 
		   $number_of_pages=ceil($total_records/$record_per_page);
		   
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
            $query_limit=($page-1)*$record_per_page;		   
		   
		   ###retrive select result
            $select_records="select * from DBS_QAQUEUE where DB_Status<>'Released' order by queuID limit $query_limit, $record_per_page";		   
		    $result=mysqli_query($conn_jira_local,$select_records);
			while ($row=mysqli_fetch_array($result))
			{
				echo $row["0"];
				?>
				<br>
				<?
				
			}
		   ###Display the links to the page 
		   for($page=1;$page<=$number_of_pages;$page++)
		   {?>
			   <a href ="demo.php?page=<?echo $page;?>"><? echo  " ".$page." "?></a>		   
		<?	   
		   }
      ?>
      
   </body>
</html>