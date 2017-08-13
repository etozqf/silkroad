<?php 
	//include 'F:\project\silkroad\code\apps\country\lib\tcpdf.php';
	include ROOT_PATH.'/apps/country/lib/tcpdf.php';
	class createPdfSearch extends TCPDF
	{
		private $data, $SelfFont, $SelfName;
		public function create($html,$dir)
		{	
				// 设置文档信息   
				$this->SetCreator('Helloweba');   
				$this->SetAuthor('silkroad');   
				$this->SetTitle('搜索结果');   
				$this->SetSubject('TCPDF Tutorial');   
				$this->SetKeywords('TCPDF, PDF, PHP');   
				   
				// 设置页眉和页脚信息   
				$this->SetHeaderData('', 0, '', '新华丝路数据库',    
				      array(0,64,255), array(0,64,128));   
				//$this->setFooterData(array(0,64,0), array(0,64,128));   
				// 设置页眉和页脚字体   
				$this->setHeaderFont(Array('stsongstdlight', '', '10'));   
				$this->setFooterFont(Array('helvetica', '', '8'));   
				   
				// 设置默认等宽字体   
				$this->SetDefaultMonospacedFont('courier');   
				   
				// 设置间距   
				$this->SetMargins(15, 27, 15);   
				$this->SetHeaderMargin(5);   
				$this->SetFooterMargin(10);   
				   
				// 设置分页   
				$this->SetAutoPageBreak(TRUE, 25);   
				   
				// set image scale factor   
				$this->setImageScale(1.25);   
				// Image example with resizing  
        //$this->tcpdf->Image('images/pdf.jpg', 10, 20, 190, 60, 'JPG', 'http://lvpad.com', '', false, 150, '', false, false, 1, false, false, false);     
				// set default font subsetting mode   
				$this->setFontSubsetting(true);   
				   
				//设置字体   
				$this->SetFont('stsongstdlight', '', 12);   
				   
				$this->AddPage();   
				//$this->Write(0,$html2,'', 0, 'L', true, 0, false, false, 0);   
				$this->WriteHTML($html);
				//$this->Output('t.pdf','I');die;
				//输出PDF
				//$filename = UPLOAD_PATH$dir.'.pdf'; 
				//$path = $dir . date('Ymd') . '/';
				//$dir = 'search/'.$this->_username;
				$path = UPLOAD_PATH.$dir.'/'.date('Ymd').'/';
	    	if(!file_exists($path)) mkdir($path, 0777, true);
	    	$fill = md5(time()).'.pdf';
	    	$this->Output($path.$fill, 'F');//输出PDF
	    	console($path.$fill);
	    	return $path.$fill;
	    	//$fill = UPLOAD_URL . $fill;   
				//$this->Output($dir.'.pdf','F'); 
				//下载pdf
				//header("Content-type:application/pdf");
				//header('Content-type: application/force-download');
				//header('Content-Disposition: attachment; filename="$fill"');   
				// // function
				//@readfile($path.$fill);

		}
	}

