<?
##Generate QA Queue Excell  
##Reason:Excel files writer 
##Auther:Parminder
##created date:6/11/2019 10:15 AM
include './config/db_conf.php';
downloadmasterexcel($Jira_local_servername, $Jira_local_username, $Jira_local_password,$Jira_local_dbname);
 
 function downloadmasterexcel($Jira_local_servername, $Jira_local_username, $Jira_local_password,$Jira_local_dbname)
 {
	 
	 ###############################Write Master report first ####################################
	$year=2018; 
	 
	  /// Create connection
$conn_jira_local =mysqli_connect($Jira_local_servername, $Jira_local_username, $Jira_local_password,$Jira_local_dbname);

// Check connection
if ($conn_jira_local->connect_error) {
    die("Connection failed: " . $conn_jira_local->connect_error);
} 
//echo "Connected successfully";
	
//FONT SETTING 

$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'F0FFFF'),
        'size'  => 10,
        'name'  => 'Georgia'
    ));	
	
	
$styleArray1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'F0FFFF'),
        'size'  => 8,
        'name'  => 'Georgia'
    ));	
	

	
	 /** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once ('../PHPExcell/PHPExcel-1.8/Classes/PHPExcel.php');

    
	$objPHPExcel_master = new PHPExcel();
	$objPHPExcel_master -> getProperties()->setCreator("Parminder")
							 ->setLastModifiedBy("Parminder")
							 ->setTitle("QA Queue")
							 ->setSubject("QA Queue")
							 ->setDescription("QA Queue")
							 ->setKeywords("office QA Queue")
							 ->setCategory("QA Queue");
	
	##ALIGN CENTER
	$style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

	####header Style 
	
	$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'F6F6F7'),
        'size'  => 9,
        'name'  => 'Georgia'
    ));
	
		$border=array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => 'DDDDDD')
            )
        )
    );
	
	 $objPHPExcel_master->getDefaultStyle()->applyFromArray($style);
							 
	###create worksheet for who caused the Issue ####

 $objWorkSheet_wci = $objPHPExcel_master->createSheet(1);
 $objPHPExcel_master->setActiveSheetIndex(1);
 ###Merge the cells for the header 
 
 $objPHPExcel_master->getActiveSheet()->mergeCells('A1:H1');
 $message="Data Systems QAQueue:".date("Y/m/d");
 $objWorkSheet_wci->setCellValue('A1',$message );
 $objPHPExcel_master->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2e5cb8');
 $objPHPExcel_master->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);  

  //Write cells
     $objWorkSheet_wci->setCellValue('A2', 'Customer Name')
            ->setCellValue('B2', 'QA Start Date')
			->setCellValue('C2', 'Activation Date')
			->setCellValue('D2', 'Database QA Priority')
            ->setCellValue('E2', 'Database Commnets')
			->setCellValue('F2', 'Team leads')
			->setCellValue('G2', 'DB Developer')
			->setCellValue('H2', 'DB Status');

	$objPHPExcel_master->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('A2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('B2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('C2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('D2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('E2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('F2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('G2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('G2')->applyFromArray($border);
	$objPHPExcel_master->getActiveSheet()->getStyle('H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2E86C1');
	$objPHPExcel_master->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);
	$objPHPExcel_master->getActiveSheet()->getStyle('H2')->applyFromArray($border);
			
		
####Set the column Width

$objPHPExcel_master->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel_master->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel_master->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

#WRAP THE SPREADSHEET 
$objPHPExcel_master->getActiveSheet()->getStyle('A1:E999')->getAlignment()->setWrapText(true); 
	
	
		$select_records="select * from DBS_QAQUEUE where DB_Status<>'Released' order by QA_Priority=0 ";		   
							$result=mysqli_query($conn_jira_local,$select_records);
							$i=3;
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
								
								####call for customer name 
								
								$customername=customername($customerid,$conn_jira_local);
								
								##find who is the teamlead and who is the develper 
						
								
								$dev_tl_name=whoisdev($customerid,$conn_jira_local);
								
								$array_dev_man=explode(':',$dev_tl_name);
								$devname=$array_dev_man[0];
								$mangname=$array_dev_man[1];
				
						
				
				$linenumber1='A'.$i;
				$linenumber2='B'.$i;
				$linenumber3='C'.$i;
				$linenumber4='D'.$i;
				$linenumber5='E'.$i;
				$linenumber6='F'.$i;
				$linenumber7='G'.$i;
				$linenumber8='H'.$i;
				
			$objWorkSheet_wci->setCellValue($linenumber1, $customername)
            ->setCellValue($linenumber2, $qastartdate)
			->setCellValue($linenumber3, $dbactivationdate)
			->setCellValue($linenumber4, $qapriority)
			->setCellValue($linenumber5, $comments)
			->setCellValue($linenumber6, $mangname)
			->setCellValue($linenumber7, $devname)
            ->setCellValue($linenumber8, $dbstatus);
			if ($i%2==0)
			{
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber1)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber2)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber3)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber4)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber4)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber5)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber5)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber6)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber6)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber7)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber7)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber8)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b3fff0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber8)->applyFromArray($border);
				
				
			}
			else 
			{
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber1)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber2)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber3)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber4)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber4)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber5)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber5)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber6)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber6)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber7)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber7)->applyFromArray($border);
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber8)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('66ffe0');
				$objPHPExcel_master->getActiveSheet()->getStyle($linenumber8)->applyFromArray($border);
				
				
			}
			
			$i=$i+1;
			}
			
	$objPHPExcel_master->getActiveSheet()->setTitle('Database QA Queue');
	$objPHPExcel_master->setActiveSheetIndex(1);
	

#################Create Excel Sheet data #################################################	
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_master, 'Excel2007');
$objWriter->save('Qaqueu'.$year.'.xlsx', __FILE__);

	 
 } 
 
 ?>