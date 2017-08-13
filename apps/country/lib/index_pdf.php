<?php 
class createPDF
{
	private $data;
	public function create()
	{	
		require_once('tcpdf.php'); 
		//实例化 
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); 
		// 设置文档信息 
		$pdf->SetCreator('Helloweba'); 
		$pdf->SetAuthor('GaoNan'); 
		$pdf->SetTitle('标题文档!'); 
		$pdf->SetSubject('TCPDF Tutorial'); 
		$pdf->SetKeywords('TCPDF, PDF, PHP'); 
		 
		// 设置页眉和页脚信息 
		$pdf->SetHeaderData('', 30, 'Helloweba.com','致力于WEB前端技术在中国的应用',array(0,64,255), array(0,64,128)); 
		// $pdf->setFooterData(array(0,64,255), array(0,64,128));
		// 设置页眉和页脚字体 
		$pdf->setHeaderFont(Array('stsongstdlight', '', '10')); 
		$pdf->setFooterFont(Array('helvetica', '', '8')); 
		 
		// 设置默认等宽字体 
		$pdf->SetDefaultMonospacedFont('courier'); 
		 
		// 设置间距 
		$pdf->SetMargins(15, 27, 15); 
		$pdf->SetHeaderMargin(5); 
		$pdf->SetFooterMargin(10); 
		 
		// 设置分页 
		$pdf->SetAutoPageBreak(TRUE, 25); 
		 
		// set image scale factor 
		$pdf->setImageScale(1.25); 
		 
		// set default font subsetting mode 
		$pdf->setFontSubsetting(true); 
		 
		//设置字体 
		$pdf->SetFont('stsongstdlight', '', 14); 
		 
		$pdf->AddPage(); 
		 
		$str1 = '<p>侯小文Helloweba.com'; 
		 
		$pdf->Write(0,$str1,'', 0, 'L', true, 0, false, false, 0); 
		 
		//输出PDF 
		$pdf->Output('20160301.pdf', 'I');
	}
}


;
$pdf = new createPDF();
$pdf->create();